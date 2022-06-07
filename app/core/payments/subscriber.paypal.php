<?php

$db = $this->DatabaseConnection;
$ApiUrl = $this->GetApiUrl('paypal');
$BearerToken = $this->PaypalAuth();

$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$UserID}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
if (!$UserQuery) {
    http_response_code(401);
    exit;
}


$PacketID = $UserQuery['user_packet'];
$UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$PacketID}'  ")->fetch(PDO::FETCH_ASSOC);

$UserJson = json_decode($UserQuery['user_extra'], true);

if (isset($UserJson['Paypal'])) :

    $SubID = $UserJson['Paypal'];

    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $ApiUrl . '/v1/billing/subscriptions/' . $SubID, [
        'http_errors' => false,
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $BearerToken
        ]
    ]);

    if ($response->getStatusCode() == 200) {

        $JsonResponse =  json_decode($response->getBody(), true);

        if ($JsonResponse['status'] == 'ACTIVE') {

            $LastPaymentAmount = $JsonResponse['billing_info']['last_payment']['amount']['value'];
            $AmountCurrency = mb_strtoupper($JsonResponse['billing_info']['last_payment']['amount']['currency_code']);
            $LastPaymentTime = $JsonResponse['billing_info']['last_payment']['time'];
            $NextPaymentTime = $JsonResponse['billing_info']['next_billing_time'];
            $UsAmount = $LastPaymentAmount;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD', [
                'http_errors' => false
            ]);

            /*
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

            /*
            if ($AmountCurrency == 'USD' && UserCurrency['currency_code'] == 'TRY') {
                sleep(1);
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD', [
                    'http_errors' => false
                ]);

                try {
                    $Body = $response->getBody();
                    $DecodeExchangeJson = json_decode($Body, true)['rates'];
                    if (isset($DecodeExchangeJson[mb_strtoupper('TRY')])) {
                        $TodayRate = $DecodeExchangeJson[mb_strtoupper('TRY')];
                    }
                    $UsAmount = $LastPaymentAmount;
                    $LastPaymentAmount = number_format($UsAmount * $TodayRate, 2);
                    $AmountCurrency = 'TRY';
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            */

            $LastPaymentTimeUnix = strtotime($LastPaymentTime);
            $NextPaymentTimeUnix = strtotime($NextPaymentTime);



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
                    $UserID, $UserQuery['user_packet'], 'paypal_' . $SubID, $LastPaymentAmount, $AmountCurrency, $UsAmount, $LastPaymentTimeUnix, $LastPaymentTimeUnix + (60 * 60 * 24 * 30), StaticFunctions::random(32)
                ));
            }
        }
    }
endif;