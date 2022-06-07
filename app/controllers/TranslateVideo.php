<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$VideoID = StaticFunctions::post('translate_video_id');
if ($VideoID == '') {
    http_response_code(401);
    exit;
}

$GetVideo = $db->query("SELECT video_translations,series_id,video_name,video_description,id FROM series_and_movies WHERE id='{$VideoID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$GetVideo) {
    http_response_code(401);
    exit;
}

$LangCode = explode('_', StaticFunctions::post('translate_lang_code'));
$langCodeR = end($LangCode);
$Name = StaticFunctions::post('translate_title');
$Desc = StaticFunctions::post('translate_description');

$VideoTranslations = ($GetVideo['video_translations'] == null) ? [] : json_decode($GetVideo['video_translations'], true);

$VideoTranslations[mb_strtolower($langCodeR)]['name'] = $Name;
$VideoTranslations[mb_strtolower($langCodeR)]['description'] = $Desc;

$TranslateVideo = $db->prepare("UPDATE series_and_movies SET
                     video_translations   = :iki
                     WHERE id = :dort");
$update = $TranslateVideo->execute(array(
    'iki' => json_encode($VideoTranslations),
    'dort' => $GetVideo['id']
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('449_the-translation-has-been-successfully'),
    'clearInput' => false,
    'closeModal' => false,
    'refreshTable' => false
]);