<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();
$ProfileToken = StaticFunctions::post('token');

$GetProfile = $db->query("SELECT * FROM profiles WHERE status=1 and user_id = '{$Me}' and profile_token='{$ProfileToken}' ")->fetch(PDO::FETCH_ASSOC);
if (!$GetProfile) {
    http_response_code(500);
    exit;
}

$delete = $db->exec("DELETE FROM profiles WHERE profile_token='{$ProfileToken}' and user_id='{$Me}' and status=1 LIMIT 1");