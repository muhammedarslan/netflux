<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();

$User = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
$Pi = $User['user_packet'];
$UserBilling = json_decode($User['user_extra'], true);
$MyPacket = $db->query("SELECT * FROM packets WHERE id='{$Pi}'  ")->fetch(PDO::FETCH_ASSOC);

if (!$User) {
    http_response_code(401);
    exit;
}

$BillingClass = new NetfluxBilling();
$BillingClass->setDb($db);

if (StaticFunctions::post('sub') == 'Paypal') {
    $BillingClass->CancelPaypalSub($UserBilling['Paypal']);
} else if (StaticFunctions::post('sub') == 'Stripe') {
    $BillingClass->CancelStripeSub($UserBilling['Stripe']);
} else {
    http_response_code(401);
    exit;
}

$FreePacket = $db->query("SELECT * FROM packets order by packet_price ASC ")->fetch(PDO::FETCH_ASSOC);

$query = $db->prepare("UPDATE users SET
user_packet = :nw
WHERE id = :nid");
$update = $query->execute(array(
    "nw" => $FreePacket['id'],
    "nid" => $User['id']
));

$Now = time();
$UpdateSubs = $db->prepare("UPDATE payments SET
payment_finish_time = :untime
WHERE  user_id='{$Me}' and payment_finish_time > $Now ");
$update = $UpdateSubs->execute(array(
    "untime" => time()
));

echo json_encode([
    'subID' => StaticFunctions::post('sub'),
    'newPacketName' => StaticFunctions::PacketTranslation($FreePacket['packet_name'], $FreePacket['packet_translations'])
]);