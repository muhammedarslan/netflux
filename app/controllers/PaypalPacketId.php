<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
$UserOldPlan = $UserQuery['user_packet'];
$MyPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserOldPlan}' ")->fetch(PDO::FETCH_ASSOC);

if ($MyPacket['packet_price'] > 0) {
    $Payments = new NetfluxBilling();
    $Payments->setDb($db);

    echo $Payments->PaypalPlanID($MyPacket['id']);
} else {
    http_response_code(401);
}
