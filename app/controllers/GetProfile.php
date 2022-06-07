<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();
$ProfileToken = StaticFunctions::post('token');

$GetProfile = $db->query("SELECT * FROM profiles WHERE status=1 and user_id = '{$Me}' and profile_token='{$ProfileToken}' ")->fetch(PDO::FETCH_ASSOC);
if ($GetProfile) {

    echo  StaticFunctions::JsonOutput([
        'Profile' => [
            'Token' => $GetProfile['profile_token'],
            'Name' => StaticFunctions::say($GetProfile['profile_name']),
            'id' => $GetProfile['id'],
            'Avatar' => $GetProfile['profile_avatar'],
            'Lang' => $GetProfile['profile_language'],
            'Level' => $GetProfile['profile_level'],
            'PlaybackController1' => $GetProfile['playback_control1'],
            'PlaybackController2' => $GetProfile['playback_control2'],
        ],
        'LangProf' => StaticFunctions::lang('58_edit'),
        'LangProf2' => StaticFunctions::lang('59_delete'),
        'ConfirmLang' => StaticFunctions::lang('60_do-you-really-want-to-delete-this')
    ]);
} else {
    http_response_code(500);
    exit;
}