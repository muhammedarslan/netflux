<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$movie_name = StaticFunctions::post('movie_name');
$movie_description = StaticFunctions::post('movie_description');
$movie_mp4 = StaticFunctions::post('movie_mp4');
$movie_short_mp4 = StaticFunctions::post('movie_short_mp4');
$s18 = StaticFunctions::post('s18');



if ($movie_name == '' || $movie_description == '' || $movie_mp4 == '' || $movie_short_mp4 == '' || $s18 == '' || !isset($_FILES['movie_images']) || !isset($_POST['movie_categories'])) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}
$CategoriesArray = [];
foreach ($_POST['movie_categories'] as $key => $category) {
    $CheckCategory = $db->query("SELECT * FROM genres WHERE id = '{$category}'")->fetch(PDO::FETCH_ASSOC);
    if (!$CheckCategory) {
        echo StaticFunctions::JsonOutput([
            'label' => 'error',
            'text' => StaticFunctions::lang('53_invalid'),
            'clearInput' => true,
            'refreshTable' => false
        ]);
        exit;
    }
    array_push($CategoriesArray, $category);
}

$files = array();
foreach ($_FILES['movie_images'] as $k => $l) {
    foreach ($l as $i => $v) {
        if (!array_key_exists($i, $files))
            $files[$i] = array();
        $files[$i][$k] = $v;
    }
}


$ImgUrls = [];
foreach ($files as $key => $image) {
    $RandomName = StaticFunctions::random_with_time(32);
    $handle = new \Verot\Upload\Upload($image);
    if ($handle->uploaded) {
        $handle->allowed = array('image/*');
        $handle->file_new_name_body   = $RandomName;
        $handle->image_convert = 'png';
        $handle->process(ROOT_DIR . '/assets/media/netflux/');
        if ($handle->processed) {
            $handle->clean();
            array_push($ImgUrls, PATH . 'assets/media/netflux/' . $RandomName . '.png');
        } else {
            echo StaticFunctions::JsonOutput([
                'label' => 'error',
                'text' => StaticFunctions::lang('46_there-was-a-problem-uploading'),
                'clearInput' => false,
                'refreshTable' => false
            ]);
            exit;
        }
    }
}

if (count($ImgUrls) < 2) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'closeModal' => false,
        'text' => StaticFunctions::lang('76_please-upload-at-least-2'),
        'clearInput' => false,
        'refreshTable' => false
    ]);
    exit;
}

//$ImgUrls = array_reverse($ImgUrls);
$VideoValid = false;

if (filter_var($movie_mp4, FILTER_VALIDATE_URL)) {
    $client = new \GuzzleHttp\Client();
    $response = $client->head($movie_mp4, [
        'http_errors' => false
    ]);
    if ($response->getStatusCode() != 404) {
        if ($response->getHeaderLine('content-type') != '') {
            $VideoValid = true;
        }
    }
}



if ($VideoValid == false) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('47_the-video-file-does-not-look-like-a'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}


$VideoValid = true;



if ($VideoValid == false) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('48_the-demo-file-does-not-look-like-a'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}


$InsertVideo = $db->prepare("INSERT INTO series_and_movies SET
    video_type = ?,
    series_id = ?,
    series_season_id = ?,
    video_name = ?,
    video_description = ?,
    video_categories = ?,
    video_main_image = ?,
    video_images = ?,
    video_level = ?,
    video_source = ?,
    video_short_source = ?,
    video_actors = ?,
    video_directors = ?,
    video_token = ?,
    created_time = ?");
$insert = $InsertVideo->execute(array(
    'movie', 0, 0, $movie_name, $movie_description, json_encode($CategoriesArray), $ImgUrls[0], json_encode($ImgUrls), $s18, $movie_mp4, $movie_short_mp4, json_encode([]), json_encode([]), StaticFunctions::random(64), time()
));


echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('82_the-new-movie-has-been-successfully'),
    'clearInput' => true,
    'refreshTable' => true
]);