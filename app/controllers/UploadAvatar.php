<?php

/* Disabled (cause of static avatars) start */
http_response_code(401);
exit;
/* Disabled (cause of static avatars) end */

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();
$Random = StaticFunctions::random(32);

$handle = new \Verot\Upload\Upload($_FILES['new_profile_avatar']);
if ($handle->uploaded) {
    $handle->allowed = array('image/*');
    $handle->file_new_name_body   = $Random;
    $handle->image_convert = 'png';
    $handle->image_resize         = true;
    $handle->image_ratio           = true;
    $handle->image_x              = 336;
    $handle->image_y              = 335;
    $handle->process(ROOT_DIR . '/assets/media/avatars/');
    if ($handle->processed) {
        $handle->clean();
        $_SESSION['NewAvatarUrl'] = PATH . 'assets/media/avatars/' . $Random . '.png';
        echo PROTOCOL . DOMAIN . PATH . 'assets/media/avatars/' . $Random . '.png';
    } else {
        http_response_code(500);
    }
}