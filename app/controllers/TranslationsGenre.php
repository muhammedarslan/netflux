<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$VideoID = StaticFunctions::post('genre');
if ($VideoID == '') {
    http_response_code(401);
    exit;
}

$GetGetre = $db->query("SELECT genres_name,id,genre_translations FROM genres WHERE id='{$VideoID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$GetGetre) {
    http_response_code(401);
    exit;
}

$J = [];

if ($GetGetre['genre_translations'] != null) {
    $J = json_decode($GetGetre['genre_translations'], true);
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
    'genre_id' => $VideoID,
    'original' => [
        'name' => StaticFunctions::say($GetGetre['genres_name'])
    ],
    'translation' => $J
]);