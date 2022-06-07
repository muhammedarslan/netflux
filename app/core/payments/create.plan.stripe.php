<?php

$db = $this->DatabaseConnection;
$StripeApiInfo = $this->StripeApi();

$GetStripeProduct = $db->query("SELECT * FROM system_settings WHERE id =1 ")->fetch(PDO::FETCH_ASSOC);
if (!$GetStripeProduct) {
    http_response_code(401);
    exit;
}


if ($GetStripeProduct['stripe_product_id'] == '') {

    $stripe = new \Stripe\StripeClient(
        $StripeApiInfo['Secret']
    );
    $response = $stripe->products->create([
        'name' => 'Netflux Video Streaming',
    ]);

    $ProductID = $response->id;

    $query = $db->prepare("UPDATE system_settings SET
        stripe_product_id = :nid");
    $update = $query->execute(array(
        "nid" => $ProductID
    ));
} else {
    $ProductID = $GetStripeProduct['stripe_product_id'];
}

$ReturnArray = [];
$BasePrice = $Price;

$GetCurrencies = $db->query("SELECT * from currencies ", PDO::FETCH_ASSOC);
if ($GetCurrencies->rowCount()) {
    foreach ($GetCurrencies as $key => $row) {
        $RowPrice = StaticFunctions::FloatPrice($BasePrice * $row['exchange_rate'], $row['rounding_type']);
        $stripe = new \Stripe\StripeClient(
            $StripeApiInfo['Secret']
        );
        $response = $stripe->prices->create([
            'unit_amount' => ($RowPrice * 100),
            'currency' => mb_strtolower($row['currency_code']),
            'recurring' => ['interval' => 'month'],
            'product' => $ProductID
        ]);

        $PlanID = $response->id;
        $ReturnArray[$row['currency_code']] = $PlanID;
    }
}


$ReturnPacketsIds =  json_encode($ReturnArray);
return $ReturnPacketsIds;