<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$NewPassword1 = StaticFunctions::post('p1');
$NewPassword2 = StaticFunctions::post('p2');

if ($NewPassword1 == '' || $NewPassword1 != $NewPassword2 || mb_strlen($NewPassword1) < 5) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text'  => StaticFunctions::lang('92_please-enter-matching-passwords-of-at'),
        'Close' => StaticFunctions::lang('93_ok')
    ]);
    exit;
}

$N = StaticFunctions::password($NewPassword1);
$query = $db->prepare("UPDATE users SET
password = :np
WHERE id = :d");
$update = $query->execute(array(
    "np" => $N,
    "d" => $Me
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text'  => StaticFunctions::lang('94_your-password-has-been-changed'),
    'Close' => StaticFunctions::lang('93_ok')
]);
exit;