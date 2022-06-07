<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$Data = StaticFunctions::post('data');
$Explode = explode('-', $Data);
$TableName = $Explode[0];
$RowID   = $Explode[1];

try {

    $CheckQuery = $db->query("SELECT * FROM $TableName WHERE id = '{$RowID}'")->fetch(PDO::FETCH_ASSOC);
    if ($CheckQuery) {
        echo StaticFunctions::ApiJson($CheckQuery);
    } else {
        http_response_code(401);
    }
} catch (\Throwable $th) {
    http_response_code(401);
}