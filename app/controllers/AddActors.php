<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();


$ID = StaticFunctions::post('video_id');

$GetVideo = $db->query("SELECT * FROM series_and_movies WHERE id = '{$ID}'")->fetch(PDO::FETCH_ASSOC);

if (!$GetVideo) {
    http_response_code(401);
    exit;
}


$DataArray = [];
if (isset($_POST['actors_actors'])) {
    foreach ($_POST['actors_actors'] as $key => $singleData) {
        $CheckData = $db->query("SELECT * FROM actors WHERE id = '{$singleData}'")->fetch(PDO::FETCH_ASSOC);
        if (!$CheckData) {
            echo StaticFunctions::JsonOutput([
                'label' => 'error',
                'text' => StaticFunctions::lang('20_invalid'),
                'clearInput' => true,
                'refreshTable' => false
            ]);
            exit;
        }
        array_push($DataArray, $singleData);
    }
}



$InsertVideo = $db->prepare(" UPDATE series_and_movies SET
    video_actors = ? WHERE id='{$ID}' ");
$insert = $InsertVideo->execute(array(
    json_encode($DataArray)
));


echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('21_the-players-have-been-successfully'),
    'clearInput' => true,
    'refreshTable' => false
]);