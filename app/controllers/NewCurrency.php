<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$CurrencyCode = StaticFunctions::post('currency_code');

$ExchangeRate = str_replace(',', '.', StaticFunctions::post('exchange_rate'));

try {
    $ExchangeRate = number_format($ExchangeRate, 2);
} catch (\Throwable $th) {
    http_response_code(401);
    exit;
}

$CurrencyList = json_decode(file_get_contents(APP_DIR . '/languages/currencies.json', true));

foreach ($CurrencyList as $key => $row) {
    $row = (array) $row;
    if ($row['code'] == $CurrencyCode) {
        $C1 = $row['code'];
        $C2 = $row['name'];
        $C3 = $row['symbol'];
        $C4 = $ExchangeRate;
        break;
    }
}

if (!isset($C1)) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$CheckCurrency = $db->query("SELECT id from currencies WHERE currency_code='{$C1}' ")->fetch(PDO::FETCH_ASSOC);

if (!$CheckCurrency) {
    $InsertData = $db->prepare("INSERT INTO currencies SET
currency_code = ?,
currency_name = ?,
currency_symbol = ?,
symbol_float = ?,
rounding_type = ?,
exchange_rate = ?");
    $insert = $InsertData->execute(array(
        $C1, $C2, $C3, StaticFunctions::post('symbol_position'), StaticFunctions::post('round_price'), $C4
    ));
}


$Packets = $db->query("SELECT * from packets WHERE packet_price > 0 ", PDO::FETCH_ASSOC);
if ($Packets->rowCount()) {
    foreach ($Packets as $key => $packet) {
        $ID = $packet['id'];
        $PaymentClass = new NetfluxBilling();
        $PaymentClass->setDb($db);

        $PaypalPlanID = $PaymentClass->PaypalCreatePlan($packet['packet_name'], $packet['packet_price']);
        $StripePlanID = $PaymentClass->StripeCreatePlan($packet['packet_name'], $packet['packet_price']);

        $InsertData = $db->prepare("UPDATE packets SET
            paypal_packet_id = ?,
            stripe_packet_id = ? WHERE id='{$ID}' ");
        $insert = $InsertData->execute(array(
            $PaypalPlanID, $StripePlanID
        ));
        sleep(1);
    }
}


echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('466_the-currency-has-been-successfully'),
    'clearInput' => false,
    'refreshTable' => true
]);