<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$season_name = StaticFunctions::post('season_name');
$season_id = StaticFunctions::post('season_series_id');

if ($season_name == '' ||  $season_id == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}

$ChekSeries = $db->query("SELECT * FROM series_and_movies WHERE video_type='series' and  id = '{$season_id}'")->fetch(PDO::FETCH_ASSOC);
if (!$ChekSeries) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('44_invalid'),
        'clearInput' => false,
        'refreshTable' => false
    ]);
    exit;
}

$CheckDuplicate = $db->query("SELECT * FROM series_and_movies WHERE video_type='season' and  video_name = '{$season_name}'")->fetch(PDO::FETCH_ASSOC);
if ($CheckDuplicate) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('88_this-season-has-already-been'),
        'clearInput' => false,
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
    'season', $ChekSeries['id'], 0, $season_name, '', json_encode([]), '', json_encode([]), 0, '', '', json_encode([]), json_encode([]), StaticFunctions::random(64), time()
));


echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('89_the-new-season-has-been-successfully'),
    'clearInput' => true,
    'refreshTable' => false
]);