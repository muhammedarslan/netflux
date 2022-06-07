<?php

class AppNotifications
{

    public static function GetNotifications($Me, $db)
    {
        $NotificationCount = 0;
        $NotificationsArray = [];
        $GetNotifications = $db->query("SELECT * FROM notifications WHERE user_id='{$Me}' and is_clicked=0 order by id DESC LIMIT 10 ", PDO::FETCH_ASSOC);
        if ($GetNotifications->rowCount()) {
            foreach ($GetNotifications as $row) {
                $NotificationCount++;
                array_push($NotificationsArray, [
                    'title' => StaticFunctions::lang($row['notification_title']),
                    'text' => StaticFunctions::lang($row['notification_text']),
                    'label' => ((array)json_decode($row['notification_label'])),
                    'time' => StaticFunctions::timerFormat($row['notification_time'], time()) . ' ' . StaticFunctions::lang('8_before'),
                    'token' => $row['notification_token']
                ]);
            }
        }

        if ($NotificationCount > 0) :
            $ButtonText = StaticFunctions::lang('9_mark-all-as');
        else :
            $ButtonText = StaticFunctions::lang('10_you-have-read-all');
        endif;

        return json_encode([
            'ButtonText' => $ButtonText,
            'NotificationCount' => $NotificationCount,
            'NotificationCount2' => $NotificationCount . ' ' . StaticFunctions::lang('11_unread'),
            'Notifications' => $NotificationsArray
        ]);
    }

    public static function SendPushNotification($FcmToken, $Title, $Text, $Token, $Image = '')
    {
        // Send Push Notification wia Firebase.
        return true;
    }

    public static function AddNotification($NotifTitle, $NotifText, $NotifUrl, $IsPush, $NotifLabel, $UserID = '-')
    {
        if ($UserID == '-') $UserID = StaticFunctions::get_id();
        global $db;
        $NotifToken = StaticFunctions::random_with_time(64);
        if ($IsPush == 1) :
            $UserInfo = $db->query("SELECT * FROM users WHERE    id = '{$UserID}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
            if ($UserInfo) :
                $TwoFactorProfile = json_decode($UserInfo['2factor_profile']);
                $FcmToken = $TwoFactorProfile->PushProfile;
                if ($FcmToken != '') :
                    self::SendPushNotification($FcmToken, $NotifTitle, $NotifText, $NotifToken);
                endif;
            endif;
        endif;

        $query = $db->prepare("INSERT INTO notifications SET
            user_id = ?,
            notification_title = ?,
            notification_text = ?,
            notification_label = ?,
            notification_time = ?,
            notification_token = ?,
            notification_url = ?,
            is_push = ?,
            is_clicked = ?,
            push_clicked = ?");
        $insert = $query->execute(array(
            $UserID, $NotifTitle, $NotifText, json_encode($NotifLabel), time(), $NotifToken, $NotifUrl, $IsPush, 0, 0
        ));

        return true;
    }

    public static function ReadAllNotifications($Me, $db)
    {
        $UpdateQuery = $db->prepare("UPDATE notifications SET
                is_clicked = :n
                WHERE user_id = :uids");
        $update = $UpdateQuery->execute(array(
            "uids" => $Me,
            "n" => 1
        ));
    }

    public static function SingleNotification($Me, $db, $token)
    {
        $SingleNotification = $db->query("SELECT * FROM notifications WHERE user_id = '{$Me}' and notification_token='{$token}' ")->fetch(PDO::FETCH_ASSOC);
        if ($SingleNotification) {

            $NotifID = $SingleNotification['id'];
            $UpdateNotif = $db->prepare("UPDATE notifications SET
             is_clicked = :n
             WHERE id = :uids");
            $update = $UpdateNotif->execute(array(
                "uids" => $NotifID,
                "n" => 1
            ));
            header("Location:" . $SingleNotification['notification_url']);
            exit;
        } else {
            header("Location:" . PATH);
            exit;
        }
    }
}
