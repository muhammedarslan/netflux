<?php

StaticFunctions::ajax_form('general');
$Email = StaticFunctions::post('email');

if ($Email == '' || !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    echo 'fail';
    exit;
} else {
    $payload = array(
        'RegisterEmail' => $Email,
        'Step' => 1,
        'TokenExpire' => time() + (60 * 10)
    );

    $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
    StaticFunctions::new_session();
    $_SESSION['AppR'] = $jwt;
    echo PATH . LANG . '/signup';
}