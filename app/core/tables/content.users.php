<?php

//sleep(1);
$DataArray = [];

$Me = StaticFunctions::get_id();

$GetData = $db->query("SELECT * FROM users WHERE status != 0 order by id DESC", PDO::FETCH_ASSOC);
if ($GetData->rowCount()) {
    foreach ($GetData as $row) {

        $PlanID = $row['user_packet'];
        $UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$PlanID}'")->fetch(PDO::FETCH_ASSOC);
        if ($UserPacket) {
            $PacketName = $UserPacket['packet_name'];
        } else {
            $PacketName = StaticFunctions::lang('131_invalid');
        }

        if ($row['user_type'] == 'admin') {
            $Ut = StaticFunctions::lang('134_manager');
        } else {
            $Ut = StaticFunctions::lang('135_member');
        }

        $id = $row['id'];
        $Avatar = $row['avatar'];
        $GetProfile = $db->query("SELECT * FROM profiles WHERE user_id = '{$id}' order by id ASC ")->fetch(PDO::FETCH_ASSOC);
        if ($GetProfile) {
            $Avatar = $GetProfile['profile_avatar'];
        }

        if ($row['status'] == 1) {
            $Stat = [
                'up',
                '<span style="color:green;font-weight:600;" >' . StaticFunctions::lang('136_active') . '</span>'
            ];
            $Li = '<li> <a onclick="BlockUser(' . $row['id'] . ');" href="javascript:;">' . StaticFunctions::lang('137_block') . '</a>
                                </li>';
        } else {
            $Stat = [
                'down',
                '<span style="color:red;font-weight:600;" >' . StaticFunctions::lang('138_passive') . '</span>'
            ];
            $Li = '<li> <a onclick="ActiveUser(' . $row['id'] . ');" href="javascript:;">' . StaticFunctions::lang('139_activate') . '</a>
                                </li>';
        }


        array_push($DataArray, [
            ' <span class="Location">' . $row['id'] . '</span>',
            '<img src="' . $Avatar . '" alt="">
                    <span class="name">' . StaticFunctions::say($row['real_name']) . '</span>
                    <span class="mail">' . $row['email'] . '</span>',
            ' <span class="Location">' . $Ut . '</span>',
            '<span class="Location">' . StaticFunctions::say($PacketName) . '</span>',
            '<span style="font-weight:600;" class="Location">' . StaticFunctions::timerFormat($row['last_login'], time()) . StaticFunctions::lang('129_before') . '</span><br>
                    <span style="margin-top:7px;display:block;" class="Location">' . $row['last_ip'] . '</span>',
            '<em style="margin-right:5px;" class="fa fa-chevron-' . $Stat[0] . '"></em>' . $Stat[1],
            '<div onclick="OpenDropdownR(this); return false;" class="dropdown js2-dropdown">
                            <div class="toggle">...</div>
                            <ul>
                                <li>
                                    <a class="js-modal-toggle" onclick="EditData(this);" data-edit="users-' . $row['id'] . '" data-target="first-modal2" href="javascript:;">' . StaticFunctions::lang('120_edit') . '</a>
                                </li>
                                ' . $Li . '
                                <li>
                                    <a onclick="DeleteData(\'User\',' . $row['id'] . ');" href="javascript:;">' . StaticFunctions::lang('121_delete') . '</a>
                                </li>
                            </ul>
                        </div>'

        ]);
    }
}