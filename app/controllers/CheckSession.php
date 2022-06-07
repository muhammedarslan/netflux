<?php

StaticFunctions::ajax_form('general');
StaticFunctions::new_session();

if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
    echo StaticFunctions::JsonOutput([
        'isLogged' => true,
        'label' => 'info',
        'message' => StaticFunctions::lang('32_you-successfully-logged-in-you-are')
    ]);
} else {
    if (isset($_SESSION['RegisterEmail']) && $_SESSION['RegisterEmail'] != '') :

        $Email = $_SESSION['RegisterEmail'];
        $_SESSION['RegisterEmail'] = '';
        unset($_SESSION['RegisterEmail']);

        $payload = array(
            'RegisterEmail' => $Email,
            'FacebookLogin' => true,
            'Step' => 2,
            'TokenExpire' => time() + (60 * 10)
        );

        $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
        StaticFunctions::new_session();
        $_SESSION['AppR'] = $jwt;

        echo StaticFunctions::JsonOutput([
            'isLogged' => false,
            'label' => 'info',
            'token' => $jwt,
            'message' => StaticFunctions::lang('33_welcome-you-are-guided-to-register')
        ]);

    else :
        http_response_code(401);
    endif;
}