<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$User = StaticFunctions::post('edit_id');


$User = $db->query("SELECT * FROM users WHERE  id = '{$User}' and status=1 ")->fetch(PDO::FETCH_ASSOC);

if (!$User) {
    http_response_code(401);
    exit;
}

$Pass = $User['password'];

if (StaticFunctions::post('edit_password') != '') {
    $Pass = StaticFunctions::password($_POST['edit_password']);
}

$ArrayControl1 = [
    'classic',
    'admin'
];

$PacketID = StaticFunctions::post('edit_user_packet');

$CheckEmail = $db->query("SELECT * FROM users WHERE email = '{$User['email']}' and id !='{$User['id']}' ")->fetch(PDO::FETCH_ASSOC);
if ($CheckEmail) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('41_this-e-mail-is-in-use-by-another'),
        'clearInput' => false,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$CheckPacket = $db->query("SELECT * FROM packets WHERE id = '{$PacketID}'")->fetch(PDO::FETCH_ASSOC);
if (!$CheckPacket) {
    http_response_code(401);
    exit;
}

if (!in_array(StaticFunctions::post('edit_user_type'), $ArrayControl1)) {
    http_response_code(401);
    exit;
}


$EditUser = $db->prepare("UPDATE users SET
                user_type = ?,
                user_packet = ?,
                password = ?,
                email = ?,
                real_name = ? WHERE id='{$User['id']}' ");
$insert = $EditUser->execute(array(
    StaticFunctions::post('edit_user_type'),
    StaticFunctions::post('edit_user_packet'),
    $Pass,
    StaticFunctions::post('edit_email'),
    StaticFunctions::post('edit_real_name')
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('57_user-successfully'),
    'clearInput' => false,
    'refreshTable' => true
]);
