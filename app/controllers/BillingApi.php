<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

if (StaticFunctions::post('mode') != 'live' && StaticFunctions::post('mode') != 'sandbox') {
    http_response_code(401);
    exit;
}


$UpdateRealName = $db->prepare("UPDATE system_settings SET
        paypal_product_id = :e,
        stripe_product_id = :e,
        app_mode = :new1,
        paypal_id = :new2,
        paypal_secret = :new3,
        stripe_id = :new4,
        stripe_secret = :new5,
        facebook_app_id = :new6,
        facebook_app_secret = :new7 ");
$update = $UpdateRealName->execute(array(
    'e' => '',
    "new1" => StaticFunctions::post('mode'),
    "new2" => StaticFunctions::post('paypal_id'),
    "new3" => StaticFunctions::post('paypal_secret'),
    "new4" => StaticFunctions::post('stripe_id'),
    "new5" => StaticFunctions::post('stripe_secret'),
    "new6" => StaticFunctions::post('facebook_app_id'),
    "new7" => StaticFunctions::post('facebook_app_secret')
));

$PaymentClass = new NetfluxBilling();
$PaymentClass->setDb($db);

$PaypalPlanID = $PaymentClass->PaypalCreatePlan('Test Plan', 1.1);
$StripePlanID = $PaymentClass->StripeCreatePlan('Test Plan', 1.1);

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('24_api-information-has-been-successfully'),
    'clearInput' => false,
    'refreshTable' => false
]);