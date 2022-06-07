<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();


if ( StaticFunctions::post('trial_period') == '' || StaticFunctions::post('trial_active') == '' ) {
    http_response_code(401);
    exit;
}

$TrialTime = number_format(StaticFunctions::post('trial_period'));

if ( StaticFunctions::post('trial_active') == 0 ) {
    $TrialTime = 0;
}


$UpdateTrialPeriod = $db->prepare("UPDATE packets SET
trial_period = :tp");
$update = $UpdateTrialPeriod->execute(array(
    "tp" => $TrialTime
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('460_trial-period-settings-successfully'),
    'clearInput' => false,
    'refreshTable' => false
]);