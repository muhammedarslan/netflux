<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$episode_name = StaticFunctions::post('edit_video_name');
$episode_description = StaticFunctions::post('edit_video_description');
$episode_mp4 = StaticFunctions::post('edit_video_source');
$episode_short_mp4 = StaticFunctions::post('edit_video_short_source');

$SeriesID = StaticFunctions::post('edit_episode_series');
$SeasonID = StaticFunctions::post('episode_season');
$EditID = StaticFunctions::post('edit_id');

$CheckEpisode = $db->query("SELECT * FROM series_and_movies WHERE id = '{$EditID}'  ")->fetch(PDO::FETCH_ASSOC);
if (!$CheckEpisode) {
    http_response_code(401);
    exit;
}

$GetSeries = $db->query("SELECT * FROM series_and_movies WHERE id = '{$SeriesID}' and video_type='series' ")->fetch(PDO::FETCH_ASSOC);
if (!$GetSeries) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('44_invalid'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}

$GetSeason = $db->query("SELECT * FROM series_and_movies WHERE id = '{$SeasonID}' and video_type='season' and series_id='{$SeriesID}' ")->fetch(PDO::FETCH_ASSOC);
if (!$GetSeason) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('45_invalid'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}

$s18 = $GetSeries['video_level'];

if ($episode_name == '' || $episode_description == '' || $episode_mp4 == '' || $episode_short_mp4 == '' || $s18 == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}

$CategoriesArray = json_decode($GetSeries['video_categories'], true);

$files = array();
if (isset($_FILES['images_inp'])) {
    foreach ($_FILES['images_inp'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (!array_key_exists($i, $files))
                $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }
}


$ImgUrls = json_decode($CheckEpisode['video_images'], true);
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
//$ImgUrls = array_reverse($ImgUrls);
$VideoValid = false;

if (filter_var($episode_mp4, FILTER_VALIDATE_URL)) {
    $client = new \GuzzleHttp\Client();
    $response = $client->head($episode_mp4, [
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

if (!isset($ImgUrls[0])) {
    $ImgUrls[0] = PATH . 'assets/media/logo.png';
}


$InsertVideo = $db->prepare("UPDATE series_and_movies SET
    series_id = ?,
    series_season_id = ?,
    video_name = ?,
    video_description = ?,
    video_categories = ?,
    video_main_image = ?,
    video_images = ?,
    video_level = ?,
    video_source = ?, video_short_source = ? WHERE id='{$EditID}' ");
$insert = $InsertVideo->execute(array(
    $GetSeries['id'], $GetSeason['id'], $episode_name, $episode_description, json_encode($CategoriesArray), $ImgUrls[0], json_encode($ImgUrls), $s18, $episode_mp4, $episode_short_mp4
));


echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('49_the-episode-has-been-successfully'),
    'clearInput' => true,
    'refreshTable' => true
]);