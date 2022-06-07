<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$BillingClass = new NetfluxBilling();
$BillingClass->setDb($db);
$ClientID = $BillingClass->PaypalClientID();

echo $ClientID;