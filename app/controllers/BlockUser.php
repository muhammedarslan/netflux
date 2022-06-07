<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$User = StaticFunctions::post('DataId');


$User = $db->query("SELECT * FROM users WHERE  id = '{$User}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
$Pi = $User['user_packet'];
$UserBilling = json_decode($User['user_extra'], true);

if (!$User) {
    http_response_code(401);
    exit;
}

$BillingClass = new NetfluxBilling();
$BillingClass->setDb($db);

if (isset($UserBilling['Paypal'])) {
    $BillingClass->CancelPaypalSub($UserBilling['Paypal']);
}

if (isset($UserBilling['Stripe'])) {
    $BillingClass->CancelStripeSub($UserBilling['Stripe']);
}



$query = $db->prepare("UPDATE users SET
status = :n
WHERE id = :ni");
$update = $query->execute(array(
    "n" => 2,
    "ni" => $User['id']
));

echo StaticFunctions::ApiJson([
    'status' => 'success',
    'label' => StaticFunctions::lang('429_successful'),
    'text'  => StaticFunctions::lang('31_the-user-has-been-blocked-their'),
    'textButton' => 'Tamam'
]);
exit;
