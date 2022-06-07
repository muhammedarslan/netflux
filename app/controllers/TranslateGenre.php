<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$GenreID = StaticFunctions::post('translate_genre_id');
if ($GenreID == '') {
    http_response_code(401);
    exit;
}

$GetGenre = $db->query("SELECT genre_translations,genres_name,id FROM genres WHERE id='{$GenreID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$GetGenre) {
    http_response_code(401);
    exit;
}

$LangCode = explode('_', StaticFunctions::post('translate_lang_code'));
$langCodeR = end($LangCode);
$Name = StaticFunctions::post('translate_title');
$Desc = StaticFunctions::post('translate_description');

$GenreTranslations = ($GetGenre['genre_translations'] == null) ? [] : json_decode($GetGenre['genre_translations'], true);

$GenreTranslations[mb_strtolower($langCodeR)]['name'] = $Name;
$GenreTranslations[mb_strtolower($langCodeR)]['description'] = $Desc;

$TranslateGenre = $db->prepare("UPDATE genres SET
                     genre_translations   = :iki
                     WHERE id = :dort");
$update = $TranslateGenre->execute(array(
    'iki' => json_encode($GenreTranslations),
    'dort' => $GetGenre['id']
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('449_the-translation-has-been-successfully'),
    'clearInput' => false,
    'closeModal' => false,
    'refreshTable' => false
]);