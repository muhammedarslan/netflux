<?php

$Me = StaticFunctions::get_id();
$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status='1' ")->fetch(PDO::FETCH_ASSOC);
$UserPacket = $UserQuery['user_packet'];
$GetPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacket}'")->fetch(PDO::FETCH_ASSOC);
$MaxProfileCount = 2;
$ProfileCount = 0;
$Profiles = [];
$Width = 0;
$AllowNew = false;
if ($GetPacket) :
    $MaxProfileCount = $GetPacket['max_profile_count'];
endif;

$GetProfiles = $db->query("SELECT * FROM profiles WHERE user_id='{$Me}' and status=1 order by id ASC ", PDO::FETCH_ASSOC);
if ($GetProfiles->rowCount()) {
    foreach ($GetProfiles as $row) {
        $ProfileCount++;
        $Width = $Width + 200;
        array_push($Profiles, $row);
    }
}

$NormalWidth = $Width;

if ($MaxProfileCount > $ProfileCount) {
    $Width = $Width + 200;
    $AllowNew = true;
}

$EditWidth = $Width;

StaticFunctions::BarbaLoaded($PageCss, $PageJs);