<?php


$Me = StaticFunctions::get_id();
$Now = time();
$UpdateSubs = $db->prepare("UPDATE payments SET
payment_finish_time = :untime
WHERE  user_id='{$Me}' and payment_finish_time > $Now ");
$update = $UpdateSubs->execute(array(
    "untime" => time()
));

try {
    $UPayments = new NetfluxBilling();
    $UPayments->setUser($UserQuery);
    $UPayments->setDb($db);
    $UPayments->PaypalSubscriber($Me);
    $UPayments->StripeSubscriber($Me);
} catch (\Throwable $th) {
    //throw $th;
}