<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();


$TrialPacket = $db->query("SELECT trial_period from packets ")->fetch(PDO::FETCH_ASSOC)['trial_period'];
$TrialTime = number_format($TrialPacket);


echo StaticFunctions::ApiJson([
    'isActive' => ($TrialTime > 0) ? '1' : '0',
    'trialTime' => $TrialTime
]);