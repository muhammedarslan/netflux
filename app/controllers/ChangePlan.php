<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();

$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
if (!$UserQuery) {
    http_response_code(401);
    exit;
}

$UserOldPlan = $UserQuery['user_packet'];
$NewPlanID   = StaticFunctions::post('packet');


if ($UserOldPlan == $NewPlanID) {
    http_response_code(401);
    exit;
}

$OldPlan = $db->query("SELECT * FROM packets WHERE id = '{$UserOldPlan}' ")->fetch(PDO::FETCH_ASSOC);
$NewPlan = $db->query("SELECT * FROM packets WHERE id = '{$NewPlanID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$OldPlan) {
    http_response_code(401);
    exit;
}

if (!$NewPlan) {
    http_response_code(401);
    exit;
}

$Payments = new NetfluxBilling();
$Payments->setUser($UserQuery);
$Payments->setDb($db);

if ($NewPlan['packet_price'] == 0) {
    $UserJson = json_decode($UserQuery['user_extra'], true);
    $SubRequired = false;
    $Payments->CancelStripeSub($UserJson['Stripe']);
    $Payments->CancelPaypalSub($UserJson['Paypal']);

    $Now = time();
    $UpdateSubs = $db->prepare("UPDATE payments SET
    payment_finish_time = :untime
    WHERE user_id='{$Me}' and payment_finish_time > $Now ");
    $update = $UpdateSubs->execute(array(
        "untime" => time()
    ));
} else {

    $UserJson = json_decode($UserQuery['user_extra'], true);
    $Err = false;
    $SubRequired = true;

    if (isset($UserJson['Paypal'])) {
        try {

            $PaypalSubID = $UserJson['Paypal'];
            $ApiUrl = $Payments->GetApiUrl('paypal');
            $BearerToken = $Payments->PaypalAuth();


            $client = new \GuzzleHttp\Client();
            $response = $client->request('PATCH', $ApiUrl . '/v1/billing/subscriptions/' . $PaypalSubID, [
                'http_errors' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Accept-Language' => 'en_US',
                    'Authorization' => 'Bearer ' . $BearerToken
                ],
                'body' => StaticFunctions::ApiJson(
                    [
                        [
                            'op' => 'replace',
                            'path' => '/plan/billing_cycles/@sequence==1/pricing_scheme/fixed_price',
                            'value' => [
                                'currency_code' => UserCurrency['currency_code'],
                                'value' => StaticFunctions::FloatPrice($NewPlan['packet_price'] * UserCurrency['exchange_rate'], UserCurrency['rounding_type'])
                            ]
                        ]
                    ]
                )
            ]);

            if ($response->getStatusCode() != 204) {
                $Err = true;
            }

            $SubRequired = false;
        } catch (\Throwable $th) {
            $Err = true;
        }
    }

    if (isset($UserJson['Stripe'])) {

        try {

            $stripe = new \Stripe\StripeClient(
                $Payments->StripeApi()['Secret']
            );
            $d = $stripe->subscriptions->retrieve(
                $UserJson['Stripe'],
                []
            );

            $DeleteItem = $d['items']['data'][0]['id'];
            $StripeSubID = $UserJson['Stripe'];

            $stripe = new \Stripe\StripeClient(
                $Payments->StripeApi()['Secret']
            );
            $UpdateStripe =  $stripe->subscriptions->update($StripeSubID, [
                'items' => [
                    ['price' => $Payments->StripePlanID($NewPlan['id'])],
                ]
            ]);

            $stripe = new \Stripe\StripeClient(
                $Payments->StripeApi()['Secret']
            );
            $stripe->subscriptionItems->delete(
                $DeleteItem,
                []
            );

            $d = $stripe->subscriptions->retrieve(
                $UserJson['Stripe'],
                []
            );

            $SubRequired = false;
        } catch (\Throwable $th) {
            $Err = true;
        }
    }
}

if (!$Err) :
    $UpdateLastPayment = $db->prepare("UPDATE users SET
    user_packet = :n
    WHERE id = :i");
    $update = $UpdateLastPayment->execute(array(
        "n" => $NewPlanID,
        "i" => $Me
    ));

    require_once CORE_DIR . '/payments/update.plan.php';

endif;

if ($SubRequired) {
    echo 'SubRequired';
} else {
    echo 0;
}