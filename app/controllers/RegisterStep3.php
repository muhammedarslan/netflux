<?php

StaticFunctions::ajax_form('general');
StaticFunctions::new_session();

$jwt = (isset($_SESSION['AppR'])) ? StaticFunctions::clear($_SESSION['AppR']) : null;

if ($jwt == null) :
    http_response_code(401);
    exit;
endif;

try {
    $DecodedHash = \Firebase\JWT\JWT::decode($jwt, StaticFunctions::JwtKey(), array('HS256'));
    $Email = $DecodedHash->RegisterEmail;
    $Expire = $DecodedHash->TokenExpire;
    $Packet = $DecodedHash->Packet;
    $Step = $DecodedHash->Step;
    if (time() > $Expire) {
        http_response_code(401);
        exit;
    }

    if ($Step != 3) {
        http_response_code(401);
        exit;
    }

    $payload = array(
        'RegisterEmail' => $Email,
        'Step' => 4,
        'Packet' => $Packet,
        'TokenExpire' => time() + (60 * 10)
    );

    $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
    $_SESSION['AppR'] = $jwt;
    echo StaticFunctions::JsonOutput([
        'status' => 'success',
        'unix' => '87654'.time()
    ]);
    exit;
} catch (\Throwable $th) {
    http_response_code(401);
    exit;
}