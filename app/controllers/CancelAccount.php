<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();

$User = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
$Pi = $User['user_packet'];
$UserBilling = json_decode($User['user_extra'], true);

if (!$User) {
    http_response_code(401);
    exit;
}

$BillingClass = new NetfluxBilling();
$BillingClass->setDb($db);
$BillingClass->CancelPaypalSub($UserBilling['Paypal']);
$BillingClass->CancelStripeSub($UserBilling['Stripe']);


$query = $db->prepare("UPDATE users SET
status = :n,
email = :e
WHERE id = :ni");
$update = $query->execute(array(
    "n" => 0,
    'e' => StaticFunctions::random('12') . '@' . DOMAIN,
    "ni" => $Me
));

session_destroy();
session_destroy();
exit;
