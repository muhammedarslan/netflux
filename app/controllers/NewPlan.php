<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$PlanName = StaticFunctions::post('plan_name');
$PlanPrice = str_replace(',', '.', StaticFunctions::post('plan_price'));
$PlanDeviceCount = str_replace(',', '.', StaticFunctions::post('max_device'));
$PlanProfileCount = str_replace(',', '.', StaticFunctions::post('max_profile'));

try {
    $PlanPrice = number_format($PlanPrice, 2);
    $PlanDeviceCount = number_format($PlanDeviceCount);
    $PlanProfileCount = number_format($PlanProfileCount);
} catch (\Throwable $th) {
    http_response_code(401);
    exit;
}

if ($PlanName == '' || $PlanPrice == '' || $PlanDeviceCount == '' || $PlanProfileCount == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$CheckData = $db->query("SELECT * FROM packets WHERE packet_name = '{$PlanName}'")->fetch(PDO::FETCH_ASSOC);
if ($CheckData) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('86_this-plan-has-already-been'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$H1 = false;
$H2 = false;

if (StaticFunctions::post('s1') == 1) $H1 = true;
if (StaticFunctions::post('s2') == 1) $H2 = true;

$EkstraArray = [
    'HD' => $H1,
    'UltraHD' => $H2,
    'AllDevices' => true,
    'UnlimitedMovies' => true,
    'CancelAnytime' => true,

];

$PaymentClass = new NetfluxBilling();
$PaymentClass->setDb($db);

$PaypalPlanID = $PaymentClass->PaypalCreatePlan($PlanName, $PlanPrice);
$StripePlanID = $PaymentClass->StripeCreatePlan($PlanName, $PlanPrice);

$TrialPeriodTime = $db->query("SELECT trial_period from packets ")->fetch(PDO::FETCH_ASSOC)['trial_period'];

$InsertData = $db->prepare("INSERT INTO packets SET
packet_name = ?,
paypal_packet_id = ?,
stripe_packet_id = ?,
max_session_count = ?,
max_profile_count = ?,
trial_period = ?,
packet_price = ?,
packet_properties = ?");
$insert = $InsertData->execute(array(
    $PlanName, $PaypalPlanID, $StripePlanID, $PlanDeviceCount, $PlanProfileCount, $TrialPeriodTime, $PlanPrice, json_encode($EkstraArray)
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('87_the-plan-has-been-created'),
    'clearInput' => true,
    'refreshTable' => true
]);