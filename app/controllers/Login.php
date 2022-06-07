<?php

StaticFunctions::ajax_form('general');
StaticFunctions::new_session();

if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
    http_response_code(401);
    exit;
} else {

    $Email = StaticFunctions::post('email');
    $Password = StaticFunctions::post('password');

    if ($Email == '' || $Password == '' || !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo StaticFunctions::JsonOutput([
            'status' => 'failed',
            'label' => 'info',
            'message' => StaticFunctions::lang('422_we-could-not-find-an-account-matching', [
                '<strong><a href="' . PATH . LANG . '">' . StaticFunctions::lang('572_here') . '</a></strong>'
            ])
        ]);
        exit;
    } else {

        $LoginType = 'Login';
        $UserQuery = $db->query("SELECT * FROM users WHERE  user_type='classic' and  email='{$Email}' ")->fetch(PDO::FETCH_ASSOC);

        if ($UserQuery) {

            if ($UserQuery['password'] != StaticFunctions::password($Password)) {
                $FailedLoginUpdate = $db->prepare("UPDATE users SET
                failed_login   = :iki
                WHERE id = :dort");
                $update = $FailedLoginUpdate->execute(array(
                    'iki' => ($UserQuery['failed_login'] + 1),
                    "dort" => $UserQuery['id']
                ));

                echo StaticFunctions::JsonOutput([
                    'status' => 'failed',
                    'label' => 'info',
                    'message' => StaticFunctions::lang('422_we-could-not-find-an-account-matching', [
                        '<strong><a href="' . PATH . LANG . '">' . StaticFunctions::lang('572_here') . '</a></strong>'
                    ])
                ]);
                exit;
            }

            if ($UserQuery['status'] == 0) {
                echo StaticFunctions::JsonOutput([
                    'status' => 'failed',
                    'label' => 'info',
                    'message' => StaticFunctions::lang('422_we-could-not-find-an-account-matching', [
                        '<strong><a href="' . PATH . LANG . '">' . StaticFunctions::lang('572_here') . '</a></strong>'
                    ])
                ]);
                exit;
            }

            if ($UserQuery['status'] != 1) {
                echo StaticFunctions::JsonOutput([
                    'status' => 'failed',
                    'label' => 'danger',
                    'message' => StaticFunctions::lang('70_this-account-has-been-blocked-please')
                ]);
                exit;
            }

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

            $payload = array(
                'UserId' => $UserQuery['id'],
                'UserIp' => StaticFunctions::get_ip(),
                'UserBrowser' => md5($_SERVER['HTTP_USER_AGENT'])
            );

            $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
            $_SESSION['SecurityHash'] = $jwt;
            session_regenerate_id();

            $UserID = $UserQuery['id'];
            $delete = $db->exec("DELETE FROM remember_me WHERE user_id = '$UserID' ");

            if (isset($_POST['remember_me']) && StaticFunctions::post('remember_me') == 'on') {

                $NewToken = StaticFunctions::random(46);

                $InsertRememberMe = $db->prepare("INSERT INTO remember_me SET
                 user_id = :bir,
                remember_token = :iki,
                 expired_time = :uc,
                user_browser = :dort");
                $insert = $InsertRememberMe->execute(array(
                    "bir" => $UserID,
                    "iki" => $NewToken,
                    "uc" => time() + 604800,
                    'dort' => md5($_SERVER['HTTP_USER_AGENT'])

                ));
                setcookie("RMB", $NewToken, time() + 604801, '/', DOMAIN, false, true);
            }

            echo StaticFunctions::JsonOutput([
                'status' => 'success',
                'label' => 'info',
                'message' => StaticFunctions::lang('32_you-successfully-logged-in-you-are')
            ]);
            exit;
        } else {
            echo StaticFunctions::JsonOutput([
                'status' => 'failed',
                'label' => 'info',
                'message' => StaticFunctions::lang('422_we-could-not-find-an-account-matching', [
                    '<strong><a href="' . PATH . LANG . '">' . StaticFunctions::lang('572_here') . '</a></strong>'
                ])
            ]);
            exit;
        }
    }
}