<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$ID = StaticFunctions::post('id');

$GetData = $db->query("SELECT * FROM series_and_movies WHERE id = '{$ID}'")->fetch(PDO::FETCH_ASSOC);
$Actors = json_decode($GetData['video_actors'], true);

$HtmlOutput = '';
$GetSeries = $db->query("SELECT * FROM actors", PDO::FETCH_ASSOC);
if ($GetSeries->rowCount()) {
    foreach ($GetSeries as $row) {
        if (in_array($row['id'], $Actors)) {
            $HtmlOutput .= '<option selected value="' . $row['id'] . '" >' . StaticFunctions::lang($row['actor_name']) . '</option>';
        } else {
            $HtmlOutput .= '<option value="' . $row['id'] . '" >' . StaticFunctions::lang($row['actor_name']) . '</option>';
        }
    }
}


echo StaticFunctions::ApiJson([
    'VideoName' => $GetData['video_name'],
    'VideoID'   => $ID,
    'HtmlSelect' => $HtmlOutput
]);