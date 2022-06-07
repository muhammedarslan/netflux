<?php
StaticFunctions::NoBarba();
http_response_code(401);
header('Content-type:application/json;charset=utf-8');
$DataArray = array(
    'HttpStatus' => 401,
    'Content-type' => 'Application/Json',
    'RequestTime' => date('d-m-Y H:i:s') . ' ' . date_default_timezone_get(),
    'Status' => 'Error',
    'Err_Messages' => ['Authentication required.']
);

echo  json_encode($DataArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;