<?php


$CheckToken = $db->query("SELECT * FROM payments_temp WHERE payment_token = '{$token}' ")->fetch(PDO::FETCH_ASSOC);
if (!$CheckToken) {
    StaticFunctions::go_home();
    exit;
}

$UserID = $CheckToken['user_id'];
$PacketID = $CheckToken['packet_id'];
$SessionID = $CheckToken['session_token'];


$DeleteTempPayment = $db->prepare("DELETE FROM payments_temp WHERE id = :id");
$delete = $DeleteTempPayment->execute(array(
    'id' => $CheckToken['id']
));


$UserQuery = $db->query("SELECT * FROM users WHERE  id = '{$UserID}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
if (!$UserQuery) {
    http_response_code(401);
    session_destroy();
    exit;
}

StaticFunctions::new_session();
$_SESSION['CheckSession'] = 'active';
$_SESSION['UserSession']    = (object) [
    'id' => $UserQuery['id'],
    'phone_code' => $UserQuery['phone_code'],
    'phone_number' => $UserQuery['phone_number'],
    'email' => $UserQuery['email'],
    'email_verify' => $UserQuery['email_verify'],
    'phone_verify' => $UserQuery['phone_verify'],
    'real_name' => $UserQuery['real_name'],
    'avatar' => $UserQuery['avatar'],
    'created_time' => $UserQuery['created_time'],
    'last_login' => $UserQuery['last_login'],
    'last_ip' => $UserQuery['last_ip'],
    'last_type' => $UserQuery['last_type'],
    'token' => $UserQuery['token']
];
$_SESSION['UserID'] = $UserQuery['id'];


$payload = array(
    'UserId' => $UserQuery['id'],
    'UserIp' => StaticFunctions::get_ip(),
    'UserBrowser' => md5($_SERVER['HTTP_USER_AGENT'])
);

$jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
$_SESSION['SecurityHash'] = $jwt;
session_regenerate_id();
sleep(1);


$Payments = new NetfluxBilling();
$Payments->setUser($UserQuery);
$Payments->setDb($db);
$StripeApiInfo = $Payments->StripeApi();

$stripe = new \Stripe\StripeClient(
    $StripeApiInfo['Secret']
);
$session = $stripe->checkout->sessions->retrieve(
    $SessionID,
    []
);

$SubID = $session->subscription;

$UserExtra = json_decode($UserQuery['user_extra'], true);

if (isset($UserExtra['Stripe'])) {
    $OldSubID = $UserExtra['Stripe'];
    $Payments->CancelStripeSub($OldSubID);
}

$UserExtra['Stripe'] = $SubID;
$NewExtra = json_encode($UserExtra);

$query = $db->prepare("UPDATE users SET
user_extra = :nw
WHERE id = :nid");
$update = $query->execute(array(
    "nw" => $NewExtra,
    "nid" => $UserQuery['id']
));


$Payments->StripeSubscriber($UserQuery['id']);
StaticFunctions::go('account');
