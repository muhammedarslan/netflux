<?php

StaticFunctions::ajax_form('general');
StaticFunctions::new_session();

if ((isset($_SESSION['SessionValidateResetPass']) && $_SESSION['SessionValidateResetPass'] == 'validated')) {

    $token = StaticFunctions::post('token');
    $p1 = StaticFunctions::post('password1');
    $p2 = StaticFunctions::post('password2');

    $N = time();
    $CheckToken = $db->query("SELECT * FROM reset_password WHERE reset_token='{$token}' and reset_time > $N ")->fetch(PDO::FETCH_ASSOC);

    if ($CheckToken) {
        $Uid = $CheckToken['user_id'];
        $CheckUser = $db->query("SELECT * FROM users WHERE    id='{$Uid}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
        if ($CheckUser) {
            if (mb_strlen($p1) < 6) {
                echo StaticFunctions::JsonOutput([
                    'status' => 'failed',
                    'message' => StaticFunctions::lang('83_please-set-a-password-of-at-least-6')
                ]);
                exit;
            }

            if ($p1 != $p2) {
                echo StaticFunctions::JsonOutput([
                    'status' => 'failed',
                    'message' => StaticFunctions::lang('84_the-passwords-entered-do-not')
                ]);
                exit;
            }

            $NewPss = StaticFunctions::password($p1);

            $_SESSION['SessionValidateResetPass'] = false;
            unset($_SESSION['SessionValidateResetPass']);

            $LastLoginUpdate = $db->prepare("UPDATE users SET
                     password   = :iki
                     WHERE id = :dort and status=1 ");
            $update = $LastLoginUpdate->execute(array(
                'iki' => $NewPss,
                'dort' => $CheckUser['id']
            ));

            StaticFunctions::AddLog(['ResetPassword' => [
                'UserId' => $CheckUser['id'],
                'UserIp' => StaticFunctions::get_ip(),
                'UserBrowser' => StaticFunctions::getBrowser(),
                'Token' => $token
            ]], $CheckUser['id']);

            $UserID = $CheckUser['id'];
            $delete = $db->exec("DELETE FROM remember_me WHERE user_id = '$UserID' ");
            $delete = $db->exec("DELETE FROM reset_password WHERE user_id = '$UserID' ");

            session_destroy();

            echo StaticFunctions::JsonOutput([
                'status' => 'success',
                'message' => StaticFunctions::lang('85_your-password-has-been-successfully')
            ]);
            exit;
        }
    }
}

http_response_code(500);
exit;
