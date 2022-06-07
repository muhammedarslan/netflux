<?php

StaticFunctions::ajax_form('general');
StaticFunctions::new_session();

$jwt = (isset($_SESSION['AppR'])) ? StaticFunctions::clear($_SESSION['AppR']) : null;

if ($jwt == null) :
    http_response_code(401);
    exit;
endif;

$_SESSION['AppR'] = null;
unset($_SESSION['AppR']);

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

    if ($Step != 4) {
        http_response_code(401);
        exit;
    }

    $Email = StaticFunctions::post('email');
    $Password = StaticFunctions::post('password');

    if ($Email == '' || $Password == ''  || !filter_var($Email, FILTER_VALIDATE_EMAIL) || strlen($Password) < 5) {
        http_response_code(401);
        exit;
    }

    $CheckEmail = $db->query("SELECT * FROM users WHERE    email = '{$Email}'")->fetch(PDO::FETCH_ASSOC);
    if ($CheckEmail) {
        http_response_code(401);
        exit;
    }

    $PacketID = $Packet;
    $CheckPacket = $db->query("SELECT * FROM packets WHERE id = '{$PacketID}'")->fetch(PDO::FETCH_ASSOC);
    if (!$CheckPacket) {
        http_response_code(401);
        exit;
    }

    $InsertUser = $db->prepare("INSERT INTO users SET
                user_type = ?,
                user_packet = ?,
                username = ?,
                authorized_login = ?,
                2factor_profile = ?,
                password = ?,
                phone_code = ?,
                email = ?,
                phone_number = ?,
                email_verify = ?,
                phone_verify = ?,
                balance = ?,
                real_name = ?,
                avatar = ?,
                created_time = ?,
                last_login = ?,
                last_ip = ?,
                last_type = ?,
                token = ?,
                user_extra = ?,
                user_currency = ?,
                failed_login = ?,
                status = ?");
    $insert = $InsertUser->execute(array(
        'classic', $PacketID, 's_' . StaticFunctions::random_with_time(12), '{"Login":true}', json_encode([]), StaticFunctions::password($Password),
        '', $Email, '', 0, 0, 0, 'First Profile', '/assets/media/default_avatar.png', time(),
        time(), StaticFunctions::get_ip(), 'Login', StaticFunctions::random(64), '[]', UserCurrency['currency_code'], 0, 1
    ));

    $SessionUser = $db->lastInsertId();
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


        if ($CheckPacket['packet_price'] < 1) {

            $InsertTrial = $db->prepare("INSERT INTO payments SET
            user_id = ?,
            payment_packet = ?,
            payment_type = ?,
            payment_amount = ?,
            payment_currency = ?,
            payment_usd = ?,
            payment_time = ?,
            payment_finish_time = ?,
            payment_token = ?");
            $insert = $InsertTrial->execute(array(
                $UserQuery['id'], $UserQuery['user_packet'], 'trial', 0.00, 'USD', 0.00, time(), time() + ($CheckPacket['trial_period'] * (60 * 60 * 24)), StaticFunctions::random(32)
            ));
        }

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
            'Type' => 'Register'
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
    }


    $PaymentManager = new NetfluxBilling();
    $PaymentManager->setUser($UserQuery);
    $PaymentManager->setDb($db);
    $BillingRequired = $PaymentManager->verifyBillingCli();

    echo StaticFunctions::JsonOutput([
        'status' => 'success',
        'paymentRequired' => $BillingRequired,
        'unix' => '87654'.time()
    ]);
    exit;
} catch (\Throwable $th) {
    http_response_code(401);
    exit;
}