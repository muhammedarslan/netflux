<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$VideoID = StaticFunctions::post('video');
if ($VideoID == '') {
    http_response_code(401);
    exit;
}

$GetVideo = $db->query("SELECT video_translations,series_id,video_name,video_description FROM series_and_movies WHERE id='{$VideoID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$GetVideo) {
    http_response_code(401);
    exit;
}

$J = [];

if ($GetVideo['video_translations'] != null) {
    $J = json_decode($GetVideo['video_translations'], true);
}

$Languages = AppLanguage::GetAllowedLangs();
$N = 0;
foreach ($Languages as $key => $value) {
    $LangCode = $value['LangFile'];
    $ex = explode('_', $LangCode);
    $Cd = mb_strtolower(end($ex));

    if (!isset($J[$Cd])) $J[$Cd] = '';
}

echo StaticFunctions::ApiJson([
    'video_id' => $VideoID,
    'original' => [
        'name' => StaticFunctions::say($GetVideo['video_name']),
        'description' => StaticFunctions::say($GetVideo['video_description'])
    ],
    'translation' => $J
]);