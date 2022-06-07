<?php

$DataArray = [];

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$TableName = StaticFunctions::post('load');

if (!isset($_POST['fast'])) {
    sleep(1);
}


if (file_exists(CORE_DIR . '/tables/content.' . $TableName . '.php')) {
    require_once CORE_DIR . '/tables/content.' . $TableName . '.php';
}


echo StaticFunctions::ApiJson([
    'data' => $DataArray
]);