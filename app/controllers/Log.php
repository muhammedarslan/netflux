<?php

StaticFunctions::ajax_form('general');
StaticFunctions::new_session();

if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') :

    $Me = StaticFunctions::get_id();
    $MyDevice = (isset($_COOKIE['AppID']) && $_COOKIE['AppID'] != '') ? StaticFunctions::clear($_COOKIE['AppID']) : null;
    $MeQuery = $db->query("SELECT id,user_packet,status,user_type FROM users WHERE id = '{$Me}' and status='1' ")->fetch(PDO::FETCH_ASSOC);
    if (!$MeQuery) {
        session_destroy();
        http_response_code(401);
        exit;
    }

    // Session manager.
    $SessionManager = new NetfluxSessionManager();
    $SessionManager->setUser($MeQuery);
    $SessionManager->setDb($db);
    $SessionManager->setCli();

    if ($SessionManager->VerifySession() == true) {
        if ($MyDevice != null) :
            $UpdateDevice = $db->prepare("UPDATE device_list SET
            last_activity = :new_activity
            WHERE device_token = :dt and user_id='{$Me}' ");
            $update = $UpdateDevice->execute(array(
                "new_activity" => time(),
                "dt" => $MyDevice
            ));
        endif;
    }

endif;