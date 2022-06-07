<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$CurrencyCode = StaticFunctions::post('code');


$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD', [
    'http_errors' => false
]);

$Body = $response->getBody();
$Decode = json_decode($Body, true)['rates'];


if (isset($Decode[$CurrencyCode])) {
    $ER = number_format($Decode[$CurrencyCode], 2);
} else {
    $ER = '';
}


echo StaticFunctions::ApiJson([
    'currency' => $ER
]);