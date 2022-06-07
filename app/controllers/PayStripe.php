<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
if (!$UserQuery) {
    http_response_code(401);
    exit;
}

$Payments = new NetfluxBilling();
$Payments->setUser($UserQuery);
$Payments->setDb($db);
$StripeApiInfo = $Payments->StripeApi();

$UserPacketID = $UserQuery['user_packet'];

$UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacketID}'")->fetch(PDO::FETCH_ASSOC);
$StripePlan = $Payments->StripePlanID($UserPacket['id']);
$PaymentRandomToken = StaticFunctions::random(64);


\Stripe\Stripe::setApiKey($StripeApiInfo['Secret']);

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $StripePlan,
            'quantity' => 1
        ]],
        'mode' => 'subscription',
        'customer_email' => $UserQuery['email'],
        'success_url' => PROTOCOL . DOMAIN . PATH . 'billing/callback/' . $PaymentRandomToken,
        'cancel_url' => PROTOCOL . DOMAIN . PATH . 'billing/stripe/failed',
    ]);
} catch (\Throwable $th) {

    $StripePlan = $Payments->StripeCreatePlan($UserPacket['packet_name'], $UserPacket['packet_price']);

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $StripePlan,
            'quantity' => 1
        ]],
        'mode' => 'subscription',
        'customer_email' => $UserQuery['email'],
        'success_url' => PROTOCOL . DOMAIN . PATH . 'billing/callback/' . $PaymentRandomToken,
        'cancel_url' => PROTOCOL . DOMAIN . PATH . 'billing/stripe/failed',
    ]);
}

$ToGoID = $session->id;
$C = $StripeApiInfo['ClientID'];

$InsertPT = $db->prepare("INSERT INTO payments_temp SET
user_id = ?,
packet_id = ?,
payment_token = ?,
session_token = ?,
created_time = ?");
$insert = $InsertPT->execute(array(
    $UserQuery['id'], $UserPacketID, $PaymentRandomToken, $ToGoID, time()
));

echo StaticFunctions::JsonOutput([
    'StripeToken' => $C,
    'PayToken' => $ToGoID
]);
