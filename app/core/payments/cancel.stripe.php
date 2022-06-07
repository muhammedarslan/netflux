<?php

$db = $this->DatabaseConnection;
$StripeApiInfo = $this->StripeApi();


try {
    $stripe = new \Stripe\StripeClient(
        $StripeApiInfo['Secret']
    );
    $res = $stripe->subscriptions->cancel(
        $OldSubID,
        []
    );
} catch (\Throwable $th) {
    //throw $th;
}

$JsEncode = '"Stripe":"' . $OldSubID . '"';
$UserQuery = $db->query("SELECT * FROM users WHERE    status =1 and user_extra LIKE '%$JsEncode%' ")->fetch(PDO::FETCH_ASSOC);
if ($UserQuery) {

    $UserExtra = json_decode($UserQuery['user_extra'], true);

    $UserExtra['Stripe'] = null;
    unset($UserExtra['Stripe']);
    $NewExtra = json_encode($UserExtra);

    $query = $db->prepare("UPDATE users SET
user_extra = :nw
WHERE id = :nid");
    $update = $query->execute(array(
        "nw" => $NewExtra,
        "nid" => $UserQuery['id']
    ));
}
