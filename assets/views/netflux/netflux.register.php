<?php

$PageCss = [];
$PageJs = [
    '/assets/netflux/js/register.js'
];

StaticFunctions::new_session();
$jwt = (isset($_SESSION['AppR'])) ? StaticFunctions::clear($_SESSION['AppR']) : null;

if ($jwt == null) :
    StaticFunctions::go(LANG);
    exit;
endif;

try {
    $DecodedHash = \Firebase\JWT\JWT::decode($jwt, StaticFunctions::JwtKey(), array('HS256'));
    $Email = $DecodedHash->RegisterEmail;
    $Expire = $DecodedHash->TokenExpire;
    $Step = $DecodedHash->Step;

    if (time() > $Expire) {
        StaticFunctions::go(LANG);
        exit;
    }
} catch (\Throwable $th) {
    StaticFunctions::go(LANG);
    exit;
}

$CheckEmail = $db->query("SELECT * FROM users WHERE  email = '{$Email}'")->fetch(PDO::FETCH_ASSOC);
if ($CheckEmail) {
    StaticFunctions::NoBarba();

    $payload = array(
        'LoginEmail' => $Email
    );
    $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
    StaticFunctions::go(PATH . LANG . '/login/' . $jwt);
    exit;
}

$route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
$route_method = $_SERVER['REQUEST_METHOD'];
$route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
$route_path = AppLanguage::UrlMaker($route_path);
$routeExplode = explode('/', $route_path);
if (!isset($routeExplode[2])) $routeExplode[2] = '';
$RegisterPathArray = [
    '' => '1',
    'packets' => '2',
    'setup' => '3',
    'account' => '4'
];

if (!isset($RegisterPathArray[$routeExplode[2]])) {
    StaticFunctions::go(LANG . '/signup');
    exit;
}

$UrlStep = $RegisterPathArray[$routeExplode[2]];

require_once StaticFunctions::View('V' . '/classic.header.php');

if ($UrlStep > $Step) {
    StaticFunctions::go(LANG . '/signup');
    exit;
} else {
    switch ($UrlStep) {
        case '1':
            require_once StaticFunctions::View('V' . '/register.step1.php');
            break;
        case '2':
            require_once StaticFunctions::View('V' . '/register.step2.php');
            break;
        case '3':
            require_once StaticFunctions::View('V' . '/register.step3.php');
            break;
        case '4':
            require_once StaticFunctions::View('V' . '/register.step4.php');
            break;

        default:
            StaticFunctions::go_home();
            break;
    }
}



require_once StaticFunctions::View('V' . '/classic.footer.php');