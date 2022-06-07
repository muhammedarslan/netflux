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

$PRID = $GetProfile['id'];

$Avatar =  PATH . 'assets/media/default_avatar.png';

if (StaticFunctions::post('avatar') != '') {
    $PostAvatar = StaticFunctions::post('avatar');
    $AvatarExplode = explode('/', $PostAvatar);
    $AvatarExplodeLast = end($AvatarExplode);
    $AvatarFilePath = ROOT_DIR . '/assets/media/avatars/' . $AvatarExplodeLast;
    if (file_exists($AvatarFilePath)) {
        $Avatar = PATH . 'assets/media/avatars/' . $AvatarExplodeLast;
    }
}


$ProfileName = StaticFunctions::post('name');
if ($ProfileName == '') {
    http_response_code(500);
    exit;
}

$ProfileLanguages =  mb_strtolower(LANG);
$Languages = AppLanguage::GetAllowedLangs();

if (isset($Languages[StaticFunctions::post('language')])) {
    $ProfileLanguages = mb_strtolower(StaticFunctions::post('language'));
}

$StandartLevel = 0;
$LevelArray = [
    0, 1, 2, 3, 4, 5
];
if (isset($LevelArray[StaticFunctions::post('level')])) {
    $StandartLevel = StaticFunctions::post('level');
}

if (StaticFunctions::post('childprofile') == 'true') {
    $StandartLevel = 5;
}

$PlaybackControl1 = 0;
$PlaybackControl2 = 0;

if (StaticFunctions::post('playbackcontrol1') == 'true') {
    $PlaybackControl1 = 1;
}

if (StaticFunctions::post('playbackcontrol2') == 'true') {
    $PlaybackControl2 = 1;
}


$InsertProfile = $db->prepare("UPDATE profiles SET
            profile_name = ?,
            profile_avatar = ?,
            profile_language = ?,
            profile_level = ?,
            playback_control1 = ?,
            playback_control2 = ? WHERE profile_token='{$ProfileToken}' and user_id='{$Me}' and status=1 ");
$insert = $InsertProfile->execute(array(
    $ProfileName, $Avatar, $ProfileLanguages, $StandartLevel, $PlaybackControl1, $PlaybackControl2
));

$UserQuery = $db->query("SELECT * FROM users WHERE id = '{$Me}' and status='1' ")->fetch(PDO::FETCH_ASSOC);

if ($UserQuery['real_name'] == 'First Profile') {
    $UpdateRealName = $db->prepare("UPDATE users SET
        real_name = :nw
        WHERE id = :ni");
    $update = $UpdateRealName->execute(array(
        "nw" => $ProfileName,
        "ni" => $UserQuery['id']
    ));
}