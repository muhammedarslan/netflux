<?php

//sleep(1);
$DataArray = [];

$Me = StaticFunctions::get_id();

$GetData = $db->query("SELECT * FROM payments order by id DESC", PDO::FETCH_ASSOC);
if ($GetData->rowCount()) {
    foreach ($GetData as $row) {

        $Uid = $row['user_id'];
        $User = $db->query("SELECT * FROM users WHERE id = '{$Uid}'")->fetch(PDO::FETCH_ASSOC);

        if (!$User) {
            $User = [
                'user_packet' => 0,
                'id' => 0,
                'avatar' => PATH . 'assets/media/default_avatar.png',
                'email' => 'null@netflux',
                'real_name' => 'Deleted User'
            ];
        }

        $PlanID = $User['user_packet'];
        $UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$PlanID}'")->fetch(PDO::FETCH_ASSOC);
        if ($UserPacket) {
            $PacketName = $UserPacket['packet_name'];
        } else {
            $PacketName = StaticFunctions::lang('131_invalid');
        }

        $id = $User['id'];
        $Avatar = $User['avatar'];
        $GetProfile = $db->query("SELECT * FROM profiles WHERE user_id = '{$id}' order by id ASC ")->fetch(PDO::FETCH_ASSOC);
        if ($GetProfile) {
            $Avatar = $GetProfile['profile_avatar'];
        } else {
            $Avatar = PATH . 'assets/media/default_avatar.png';
        }

        $Stat = [
            'up',
            '<span style="color:green;font-weight:600;" >' . StaticFunctions::lang('132_successful') . '</span>'
        ];


        array_push($DataArray, [
            ' <span class="Location">' . $row['id'] . '</span>',
            '<img src="' . $Avatar . '" alt="">
                    <span class="name">' . StaticFunctions::say($User['real_name']) . '</span>
                    <span class="mail">' . $User['email'] . '</span>',
            '<span class="Location">' . date('d-m-Y H:i:s', $row['payment_time']) . '</span>',
            '<span class="Location">' . StaticFunctions::say($PacketName) . '</span>',
            '<span class="Location">' . StaticFunctions::say($row['payment_type']) . '</span>',
            '<span class="Location">$' . number_format($row['payment_usd'], 2) . '</span>',
            '<em style="margin-right:5px;" class="fa fa-chevron-' . $Stat[0] . '"></em>' . $Stat[1]

        ]);
    }
}
