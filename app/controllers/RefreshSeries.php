<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();


$HtmlOutput = '';
$GetSeries = $db->query("SELECT * FROM series_and_movies WHERE video_type='series' ", PDO::FETCH_ASSOC);
if ($GetSeries->rowCount()) {
    $HtmlOutput .= '<option value="" disabled selected>' . StaticFunctions::lang('97_please-select-a') . '</option>';
    foreach ($GetSeries as $row) {
        $HtmlOutput .= '<option value="' . $row['id'] . '" >' . StaticFunctions::lang($row['video_name']) . '</option>';
    }
}

if ($HtmlOutput == '') {
    $HtmlOutput .= '<option disabled selected>' . StaticFunctions::lang('98_please-add-a-string') . '</option>';
}

echo $HtmlOutput;