<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$Data = StaticFunctions::post('data');
$Explode = explode('-', $Data);
$TableName = $Explode[0];
$RowID   = $Explode[1];

$CheckQuery = $db->query("SELECT * FROM series_and_movies WHERE id = '{$RowID}'")->fetch(PDO::FETCH_ASSOC);
$SeriesID = $CheckQuery['series_id'];
$SeasonID = $CheckQuery['series_season_id'];

$HtmlOutputSeries = '';
$GetSeries = $db->query("SELECT * FROM series_and_movies WHERE video_type='series' ", PDO::FETCH_ASSOC);
if ($GetSeries->rowCount()) {
    foreach ($GetSeries as $row) {
        if ($row['id'] == $SeriesID) {
            $HtmlOutputSeries .= '<option selected value="' . $row['id'] . '" >' . StaticFunctions::lang($row['video_name']) . '</option>';
        } else {
            $HtmlOutputSeries .= '<option value="' . $row['id'] . '" >' . StaticFunctions::lang($row['video_name']) . '</option>';
        }
    }
}

if ($HtmlOutputSeries == '') {
    $HtmlOutputSeries .= '<option disabled selected>' . StaticFunctions::lang('98_please-add-a-string') . '</option>';
}

$HtmlOutputSeason = '';
$GetSeries = $db->query("SELECT * FROM series_and_movies WHERE video_type='season' and series_id='{$SeriesID}' ", PDO::FETCH_ASSOC);
if ($GetSeries->rowCount()) {
    foreach ($GetSeries as $row) {
        if ($row['id'] == $SeasonID) {
            $HtmlOutputSeason .= '<option selected value="' . $row['id'] . '" >' . StaticFunctions::lang($row['video_name']) . '</option>';
        } else {
            $HtmlOutputSeason .= '<option value="' . $row['id'] . '" >' . StaticFunctions::lang($row['video_name']) . '</option>';
        }
    }
}

if ($HtmlOutputSeason == '') {
    $HtmlOutputSeason .= '<option disabled selected>' . StaticFunctions::lang('98_please-add-a-string') . '</option>';
}

echo StaticFunctions::ApiJson([
    'SeriesHtml' => $HtmlOutputSeries,
    'SeasonHtml' => $HtmlOutputSeason
]);