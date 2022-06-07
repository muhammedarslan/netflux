<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$LangCode = StaticFunctions::post('DataId');


$GetData = [];

$Languages = AppLanguage::GetAllowedLangs();

unset($Languages[$LangCode]);

@unlink(APP_DIR . '/languages/languages.json');
sleep(1);
file_put_contents(APP_DIR . '/languages/languages.json', json_encode($Languages));

echo StaticFunctions::ApiJson([
    'status' => 'success',
    'label' => StaticFunctions::lang('429_successful'),
    'text'  => StaticFunctions::lang('38_data-has-been-successfully'),
    'textButton' => 'Tamam'
]);
exit;