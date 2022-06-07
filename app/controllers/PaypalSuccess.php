<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$SubID = StaticFunctions::post('subID');

if ($SubID == '') {
    http_response_code(401);
    exit;
}


$UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
if (!$UserQuery) {
    http_response_code(401);
    exit;
}

$Payments = new NetfluxBilling();
$Payments->setUser($UserQuery);
$Payments->setDb($db);

$UserExtra = json_decode($UserQuery['user_extra'], true);

if (isset($UserExtra['Paypal'])) {
    $OldSubID = $UserExtra['Paypal'];
    $Payments->CancelPaypalSub($OldSubID);
}

$UserExtra['Paypal'] = $SubID;
$NewExtra = json_encode($UserExtra);

$query = $db->prepare("UPDATE users SET
user_extra = :nw
WHERE id = :nid");
$update = $query->execute(array(
    "nw" => $NewExtra,
    "nid" => $UserQuery['id']
));

sleep(1);
$Payments->PaypalSubscriber($Me);
