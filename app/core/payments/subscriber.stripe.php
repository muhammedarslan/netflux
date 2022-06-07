<?php

$db = $this->DatabaseConnection;
$StripeApiInfo = $this->StripeApi();

$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$UserID}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
if (!$UserQuery) {
    http_response_code(406);
    exit;
}

$PacketID = $UserQuery['user_packet'];
$UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$PacketID}'  ")->fetch(PDO::FETCH_ASSOC);

$UserJson = json_decode($UserQuery['user_extra'], true);

if (isset($UserJson['Stripe'])) :

    $SubID = $UserJson['Stripe'];

    $stripe = new \Stripe\StripeClient(
        $StripeApiInfo['Secret']
    );
    $JsonResponse = $stripe->subscriptions->retrieve(
        $SubID,
        []
    );


    if ($JsonResponse->status == 'active') {

        $LastPaymentAmount = number_format(($JsonResponse->plan->amount / 100), 2);
        $AmountCurrency = mb_strtoupper($JsonResponse->plan->currency);
        $LastPaymentTimeUnix = $JsonResponse->current_period_start;
        $NextPaymentTimeUnix = $JsonResponse->current_period_end;
        $UsAmount = $LastPaymentAmount;

        /*
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD', [
            'http_errors' => false
        ]);

        try {
            $Body = $response->getBody();
            $DecodeExchangeJson = json_decode($Body, true)['rates'];

            if (isset($DecodeExchangeJson[mb_strtoupper($AmountCurrency)])) {
                $TodayRate = $DecodeExchangeJson[mb_strtoupper($AmountCurrency)];
                $UsAmount = number_format($LastPaymentAmount / $TodayRate, 2);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        */

        $UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$UserID}'")->fetch(PDO::FETCH_ASSOC);
        $PacketID = $UserQuery['user_packet'];
        $Packet = $db->query("SELECT * FROM packets WHERE id = '{$PacketID}'")->fetch(PDO::FETCH_ASSOC);
        $Now = time();
        $CheckInsert = $db->query("SELECT * FROM payments WHERE user_id = '{$UserID}' and payment_packet='{$PacketID}' and payment_amount='{$LastPaymentAmount}' and payment_time='{$LastPaymentTimeUnix}' and payment_finish_time > $Now ")->fetch(PDO::FETCH_ASSOC);
        if (!$CheckInsert) {
            $InsertTrial = $db->prepare("INSERT INTO payments SET
            user_id = ?,
            payment_packet = ?,
            payment_type = ?,
            payment_amount = ?,
            payment_currency = ?,
            payment_usd = ?,
            payment_time = ?,
            payment_finish_time = ?,
            payment_token = ?");
            $insert = $InsertTrial->execute(array(
                $UserID, $UserQuery['user_packet'], 'stripe_' . $SubID, $LastPaymentAmount, $AmountCurrency, $UsAmount, $LastPaymentTimeUnix, $NextPaymentTimeUnix, StaticFunctions::random(32)
            ));
        }
    }
endif;