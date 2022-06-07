<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$series_name = StaticFunctions::post('series_name');
$s18 = StaticFunctions::post('s18');


if ($series_name == '' ||  $s18 == '' || !isset($_POST['series_categories'])) {
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
foreach ($_POST['series_categories'] as $key => $category) {
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

$CheckDuplicate = $db->query("SELECT * FROM series_and_movies WHERE video_type='series' and  video_name = '{$series_name}'")->fetch(PDO::FETCH_ASSOC);
if ($CheckDuplicate) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('90_this-array-has-already-been'),
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
    'series', 0, 0, $series_name, '', json_encode($CategoriesArray), '', json_encode([]), $s18, '', '', json_encode([]), json_encode([]), StaticFunctions::random(64), time()
));


echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('91_the-new-array-has-been-successfully'),
    'clearInput' => true,
    'refreshTable' => false
]);