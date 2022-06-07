<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$LangCode = StaticFunctions::post('data');


$GetData = [];

$Languages = AppLanguage::GetAllowedLangs();

echo StaticFunctions::ApiJson([
    'language_name' => $Languages[$LangCode]['LangName'],
    'language_code' => $Languages[$LangCode]['LangFile']
]);