<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$VideoID = StaticFunctions::post('video_id');
if ($VideoID == '') {
    http_response_code(401);
    exit;
}

$GetVideo = $db->query("SELECT * FROM series_and_movies WHERE id='{$VideoID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$GetVideo) {
    http_response_code(401);
    exit;
}


require_once CDIR . '/class.stream.creator.php';
$CreateStream = new NetfluxStreamCreator();
$CreateStream->setDb($db);
$CreateStream->setVideo($GetVideo);

try {
    $CreateStream->StreamCreator();
    echo StaticFunctions::JsonOutput([
        'label' => 'success',
        'text' => StaticFunctions::lang('36_the-post-has-been-successfully-created'),
        'clearInput' => false,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
} catch (\Throwable $th) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('37_there-was-a-problem-processing-the'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}