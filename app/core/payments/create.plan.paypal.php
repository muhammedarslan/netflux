<?php


$db = $this->DatabaseConnection;
$ApiUrl = $this->GetApiUrl('paypal');
$BearerToken = $this->PaypalAuth();

$GetPaypalProduct = $db->query("SELECT * FROM system_settings WHERE id =1 ")->fetch(PDO::FETCH_ASSOC);
if (!$GetPaypalProduct) {
  http_response_code(401);
  exit;
}


if ($GetPaypalProduct['paypal_product_id'] == '') {

  $client = new \GuzzleHttp\Client();
  $response = $client->request('POST', $ApiUrl . '/v1/catalogs/products', [
    'http_errors' => false,
    'headers' => [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      'Accept-Language' => 'en_US',
      'Authorization' => 'Bearer ' . $BearerToken
    ],
    'body' => StaticFunctions::ApiJson(
      [
        'name' => 'Netflux Video Streaming Service',
        'type' => 'SERVICE',
        'category' => 'DIGITAL_MEDIA_BOOKS_MOVIES_MUSIC',
      ]
    )
  ]);

  if ($response->getStatusCode() != 201) {
    http_response_code(401);
    exit;
  }

  $JsonResponse =  json_decode($response->getBody(), true);
  $ProductID = $JsonResponse['id'];

  $query = $db->prepare("UPDATE system_settings SET
        paypal_product_id = :nid");
  $update = $query->execute(array(
    "nid" => $ProductID
  ));
} else {
  $ProductID = $GetPaypalProduct['paypal_product_id'];
}

$ReturnArray = [];
$BasePrice = $Price;
$UsPlan = '';

$GetCurrencies = $db->query("SELECT * from currencies ", PDO::FETCH_ASSOC);
if ($GetCurrencies->rowCount()) {
  foreach ($GetCurrencies as $key => $row) {
    $RowPrice = StaticFunctions::FloatPrice($BasePrice * $row['exchange_rate'], $row['rounding_type']);

    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', $ApiUrl . '/v1/billing/plans', [
      'http_errors' => false,
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Accept-Language' => 'en_US',
        'Authorization' => 'Bearer ' . $BearerToken
      ],
      'body' => '{
  "product_id": "' . $ProductID . '",
  "name": "' . $Name . '",
  "description": "Video Streaming Service ' . $Name . ' plan",
  "status": "ACTIVE",
  "billing_cycles": [
    {
      "frequency": {
        "interval_unit": "MONTH",
        "interval_count": 1
      },
      "tenure_type": "REGULAR",
      "sequence": 1,
      "total_cycles": 12,
      "pricing_scheme": {
        "fixed_price": {
          "value": "' . $RowPrice . '",
          "currency_code": "' . $row['currency_code'] . '"
        }
      }
    }
  ],
  "payment_preferences": {
    "payment_failure_threshold": 0
  }
}'
    ]);

    if ($response->getStatusCode() != 201) {
      $ReturnArray[$row['currency_code']] = $UsPlan;
      continue;
    }
    $JsonResponse =  json_decode($response->getBody(), true);
    $PlanID = $JsonResponse['id'];
    if ($UsPlan == '') $UsPlan = $PlanID;
    $ReturnArray[$row['currency_code']] = $PlanID;
  }
}


$ReturnPacketsIds =  json_encode($ReturnArray);
return $ReturnPacketsIds;