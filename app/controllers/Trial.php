<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$UserID = StaticFunctions::get_id();

$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$UserID}'")->fetch(PDO::FETCH_ASSOC);
$PacketID = $UserQuery['user_packet'];
$Packet = $db->query("SELECT * FROM packets WHERE id = '{$PacketID}'")->fetch(PDO::FETCH_ASSOC);
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
    $UserID, $UserQuery['user_packet'], 'trial', 0.00, 'USD', 0.00, time(), time() + ($Packet['trial_period'] * (60 * 60 * 24)), StaticFunctions::random(32)
));

echo StaticFunctions::JsonOutput([
    'welcome' => 'browse'
]);