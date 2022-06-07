<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$User = StaticFunctions::post('profile_id');


$User = $db->query("SELECT * FROM users WHERE  id = '{$User}' and status=1 ")->fetch(PDO::FETCH_ASSOC);

if (!$User) {
    http_response_code(401);
    exit;
}

$Pass = $User['password'];

if (StaticFunctions::post('profile_password') != '') {
    $Pass = StaticFunctions::password($_POST['profile_password']);
}

$ArrayControl1 = [
    'classic',
    'admin'
];


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


$EditUser = $db->prepare("UPDATE users SET
                password = ?,
                email = ?,
                real_name = ? WHERE id='{$User['id']}' ");
$insert = $EditUser->execute(array(
    $Pass,
    StaticFunctions::post('profile_email'),
    StaticFunctions::post('profile_real_name')
));

StaticFunctions::new_session();

$_SESSION['UserSession']->real_name = StaticFunctions::post('profile_real_name');
$_SESSION['UserSession']->email = StaticFunctions::post('profile_email');

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('42_your-profile-has-been-successfully'),
    'clearInput' => false,
    'refreshTable' => false
]);