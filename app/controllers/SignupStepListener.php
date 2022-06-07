<?php

StaticFunctions::ajax_form('general');
StaticFunctions::new_session();

$jwt = (isset($_SESSION['AppR'])) ? StaticFunctions::clear($_SESSION['AppR']) : null;

if ($jwt == null) :
    http_response_code(401);
    exit;
endif;

$route_path = StaticFunctions::post('currentPage');
$routeExplode = explode('/', $route_path);
if (!isset($routeExplode[3])) $routeExplode[3] = '';
$RegisterPathArray = [
    '' => '1',
    'packets' => '2',
    'setup' => '3',
    'account' => '4'
];

if (!isset($RegisterPathArray[$routeExplode[3]])) {
    http_response_code(401);
    exit;
}

$UrlStep = $RegisterPathArray[$routeExplode[3]];
$selectedPakcet = null;

try {
    $DecodedHash = \Firebase\JWT\JWT::decode($jwt, StaticFunctions::JwtKey(), array('HS256'));
    $Email = $DecodedHash->RegisterEmail;
    $Expire = $DecodedHash->TokenExpire;
    $Step = $DecodedHash->Step;
    if (time() > $Expire) {
        http_response_code(401);
        exit;
    }

    if ($UrlStep != $Step && $Step > $UrlStep) {
        $DecodedHash->Step = $UrlStep;
        $payload = (array) $DecodedHash;

        $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
        $_SESSION['AppR'] = $jwt;
    }

    if (isset($DecodedHash->Packet)) {
        $selectedPakcet = $DecodedHash->Packet;
    }

    echo StaticFunctions::JsonOutput([
        'process' => 'success',
        'selectedPacket' => $selectedPakcet,
        'currentStep' => $UrlStep
    ]);
    exit;
} catch (\Throwable $th) {
    http_response_code(401);
    exit;
}