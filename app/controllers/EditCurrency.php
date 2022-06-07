<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$ExchangeRate = str_replace(',', '.', StaticFunctions::post('edit_exchange_rate'));
$ID = StaticFunctions::post('edit_id');

try {
    $ExchangeRate = number_format($ExchangeRate, 2);
} catch (\Throwable $th) {
    http_response_code(401);
    exit;
}


$InsertData = $db->prepare("UPDATE currencies SET
exchange_rate = ?,symbol_float=?,rounding_type = ? WHERE id='{$ID}' ");
$insert = $InsertData->execute(array(
    $ExchangeRate, StaticFunctions::post('edit_symbol_float'), post('edit_rounding_type')
));

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
    'text' => StaticFunctions::lang('467_the-currency-has-been-successfully'),
    'clearInput' => false,
    'refreshTable' => true
]);