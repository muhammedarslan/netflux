<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$GroupName = StaticFunctions::post('avatar_group_select');

if ($GroupName == '__new__') {
    $GroupName = StaticFunctions::post('avatar_group');
}

if ($GroupName == '') {
    http_response_code(401);
    exit;
}

$AvatarPath = null;
$Random = StaticFunctions::random(32);
$handle = new \Verot\Upload\Upload($_FILES['avatar_img']);
if ($handle->uploaded) {
    $handle->allowed = array('image/*');
    $handle->file_new_name_body   = $Random;
    $handle->image_convert = 'png';
    $handle->image_resize         = true;
    $handle->image_ratio           = true;
    $handle->image_x              = 200;
    $handle->image_y              = 200;
    $handle->process(ROOT_DIR . '/assets/media/avatars/');
    if ($handle->processed) {
        $handle->clean();
        $AvatarPath = PATH . 'media/avatars/' . $Random . '.png';
    } else {
        http_response_code(500);
        exit;
    }
}

if ($AvatarPath == null) {
    http_response_code(401);
    exit;
}

$query = $db->prepare("INSERT INTO avatars SET
avatar_group = ?,
avatar_path = ?,
created_time = ?");
$insert = $query->execute(array(
    $GroupName, $AvatarPath, time()
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('526_the-new-visual-has-been-successfully'),
    'clearInput' => false,
    'refreshTable' => false
]);