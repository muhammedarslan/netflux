<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$UserID = StaticFunctions::post('DataId');


$User = $db->query("SELECT * FROM users WHERE id = '{$UserID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$User) {
    http_response_code(401);
    exit;
}


$query = $db->prepare("UPDATE users SET
status = :n
WHERE id = :ni");
$update = $query->execute(array(
    "n" => 1,
    "ni" => $User['id']
));


echo StaticFunctions::ApiJson([
    'status' => 'success',
    'label' => StaticFunctions::lang('429_successful'),
    'text'  => StaticFunctions::lang('19_the-user-has-been-successfully'),
    'textButton' => 'Tamam'
]);
exit;
