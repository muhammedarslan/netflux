<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$LangName = StaticFunctions::post('edit_language_name');
$LangCode = StaticFunctions::post('edit_language_code');

if ($LangName == '' || $LangCode == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$GetData = [];

$Languages = AppLanguage::GetAllowedLangs();
$Exp = explode('_', $LangCode);
$Cd = mb_strtolower($Exp[1]);

$Languages[$Cd] = [
    'LangName' => $LangName,
    'LangFile' => $LangCode
];

@unlink(APP_DIR . '/languages/languages.json');
sleep(1);
file_put_contents(APP_DIR . '/languages/languages.json', json_encode($Languages));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('52_the-language-has-been-successfully'),
    'clearInput' => true,
    'refreshTable' => true
]);