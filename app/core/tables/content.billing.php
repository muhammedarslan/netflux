<?php

//sleep(1);
$DataArray = [];

$Me = StaticFunctions::get_id();

$GetPayments = $db->query("SELECT * FROM payments WHERE user_id='{$Me}' and payment_amount > 0 order by id ASC ", PDO::FETCH_ASSOC);
if ($GetPayments->rowCount()) {
    foreach ($GetPayments as $row) {

        if (strstr($row['payment_type'], 'paypal_')) {
            $img = PATH . 'assets/media/b_paypal.png';
        } else if (strstr($row['payment_type'], 'stripe_')) {
            $img = PATH . 'assets/media/b_stripe.png';
        } else {
            $img = PATH . 'assets/netflux/images/logo.png';
        }

        $Pt = $row['payment_type'];


        $UserPacket = $db->query("SELECT packet_name,packet_translations FROM packets WHERE id = '{$row['payment_packet']}'  ")->fetch(PDO::FETCH_ASSOC);


        if ($Pt == 'from_previous_subscription') $Pt = StaticFunctions::lang('122_package');
        $explode = explode('_', $Pt);

        $Symbol = $db->query("SELECT currency_symbol,symbol_float from currencies WHERE currency_code='{$row['payment_currency']}' ")->fetch(PDO::FETCH_ASSOC);

        $TablePrice = StaticFunctions::ShowPrice(number_format($row['payment_amount'], 2), $Symbol['currency_symbol'], $Symbol['symbol_float']);

        array_push($DataArray, [
            '#' . $row['id'],
            '<img width="80px" src="' . $img . '"/>',
            StaticFunctions::PacketTranslation($UserPacket['packet_name'], $UserPacket['packet_translations']),
            end($explode),
            $TablePrice,
            date('d-m-Y', $row['payment_time']),
            date('d-m-Y', $row['payment_finish_time'])
        ]);
    }
}

$DataJson = json_encode([
    'data' => $DataArray
]);