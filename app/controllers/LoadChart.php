<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$DataArray = [];


$GetLastMonthPayments = $db->query("SELECT * FROM payments WHERE payment_amount > 0 order by id DESC LIMIT 20  ", PDO::FETCH_ASSOC);
if ($GetLastMonthPayments->rowCount()) {
    foreach ($GetLastMonthPayments as $row) {
        $rowDate = date('Y-m-d', $row['payment_time']);
        if (isset($DataArray[$rowDate])) {
            $DataArray[$rowDate] += $row['payment_usd'];
        } else {
            $DataArray[$rowDate] = $row['payment_usd'];
        }
    }
}

$Data1 = [];
$Data2 = [];
$Max = 20;

foreach ($DataArray as $key => $value) {
    array_push($Data1, $key);
    array_push($Data2, number_format($value, 2));

    if ($value > $Max) {
        $Max = number_format($value + 10);
    }
}

$Lgn = LANG;

if ($Lgn == 'gb') {
    $Lgn = 'en';
}

if ($Lgn == 'us') {
    $Lgn = 'en';
}

echo StaticFunctions::ApiJson([
    'Data1' => $Data1,
    'Data2' => $Data2,
    'Text' => StaticFunctions::lang('67_earnings'),
    'Text2' => StaticFunctions::lang('68_dollar'),
    'Title' => StaticFunctions::lang('69_earnings'),
    'Lang' => $Lgn,
    'Max'  => (int) $Max
]);
