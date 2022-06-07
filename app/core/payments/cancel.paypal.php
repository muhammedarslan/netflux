<?php

// $OldSubID

$db = $this->DatabaseConnection;
$ApiUrl = $this->GetApiUrl('paypal');
$BearerToken = $this->PaypalAuth();


$client = new \GuzzleHttp\Client();
$response = $client->request('POST', $ApiUrl . '/v1/billing/subscriptions/' . $OldSubID . '/cancel', [
    'http_errors' => false,
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Accept-Language' => 'en_US',
        'Authorization' => 'Bearer ' . $BearerToken
    ],
    'body' => StaticFunctions::ApiJson(
        [
            'reason' => 'On user request',
        ]
    )
]);


$JsEncode = '"Paypal":"' . $OldSubID . '"';
$UserQuery = $db->query("SELECT * FROM users WHERE    status =1 and user_extra LIKE '%$JsEncode%' ")->fetch(PDO::FETCH_ASSOC);
if ($UserQuery) {

    $UserExtra = json_decode($UserQuery['user_extra'], true);

    $UserExtra['Paypal'] = null;
    unset($UserExtra['Paypal']);
    $NewExtra = json_encode($UserExtra);

    $query = $db->prepare("UPDATE users SET
user_extra = :nw
WHERE id = :nid");
    $update = $query->execute(array(
        "nw" => $NewExtra,
        "nid" => $UserQuery['id']
    ));
}
