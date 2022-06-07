<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();


$Me = StaticFunctions::get_id();

$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status='1' ")->fetch(PDO::FETCH_ASSOC);
$UserPacket = $UserQuery['user_packet'];
$GetPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacket}'")->fetch(PDO::FETCH_ASSOC);
$MaxProfileCount = 2;
$ProfileCount = 0;
if ($GetPacket) :
    $MaxProfileCount = $GetPacket['max_profile_count'];
endif;

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

$Profiles = [];
$GetProfiles = $db->query("SELECT * FROM profiles WHERE user_id='{$Me}' and status=1 ", PDO::FETCH_ASSOC);
if ($GetProfiles->rowCount()) {
    foreach ($GetProfiles as $row) {
        $ProfileCount++;
        array_push($Profiles, $row);

        if ($row['profile_name'] == $ProfileName) {
            http_response_code(401);
            exit;
        }
    }
}



if ($MaxProfileCount > $ProfileCount) {

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

    $InsertProfile = $db->prepare("INSERT INTO profiles SET
            user_id = ?,
            profile_name = ?,
            profile_avatar = ?,
            profile_language = ?,
            profile_level = ?,
            playback_control1 = ?,
            playback_control2 = ?,
            profile_token = ?,
            created_time = ?,
            status = ?");
    $insert = $InsertProfile->execute(array(
        $Me, $ProfileName, $Avatar, $ProfileLanguages, $StandartLevel, $PlaybackControl1, $PlaybackControl2, StaticFunctions::random(32), time(), 1
    ));

    if ($UserQuery['real_name'] == 'First Profile') {
        $UpdateRealName = $db->prepare("UPDATE users SET
        real_name = :nw
        WHERE id = :ni");
        $update = $UpdateRealName->execute(array(
            "nw" => $ProfileName,
            "ni" => $UserQuery['id']
        ));
    }
} else {
    http_response_code(500);
    exit;
}