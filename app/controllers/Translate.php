<?php

$DataArray = [];

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$JsonFile = StaticFunctions::post('json_file');
$AllPost = StaticFunctions::clear($_POST);
unset($AllPost['json_file']);

$Exp = explode('_', StaticFunctions::post('json_file'));
$Cd = mb_strtolower($Exp[1]);
$LangArray = AppLanguage::LanguageSingleJson($Cd);
$TRLang = AppLanguage::LanguageJson();

foreach ($TRLang as $key => $value) {
    $LangArray[$key] = $AllPost[$key];
}

@unlink(APP_DIR . '/languages/' . $JsonFile . '.json');
sleep(1);

file_put_contents(APP_DIR . '/languages/' . $JsonFile . '.json', json_encode($LangArray));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('430_language-translation-completed'),
    'clearInput' => false,
    'refreshTable' => false
]);