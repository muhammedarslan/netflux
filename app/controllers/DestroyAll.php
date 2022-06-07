<?php

StaticFunctions::ajax_form('private');
$UserID = StaticFunctions::get_id();
$DeviceID = (isset($_COOKIE['AppID']) && $_COOKIE['AppID'] != '') ? StaticFunctions::clear($_COOKIE['AppID']) : '000';

$UpdateSessions = $db->prepare("UPDATE device_list SET
status = :newStat
WHERE user_id='{$UserID}' and device_token != '{$DeviceID}' ");
$update = $UpdateSessions->execute(array(
    "newStat" => 0
));

echo StaticFunctions::ApiJson([
    'process' => 'completed'
]);