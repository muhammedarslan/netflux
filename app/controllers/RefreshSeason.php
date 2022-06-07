<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Series = StaticFunctions::post('id');


$HtmlOutput = '';
$GetSeries = $db->query("SELECT * FROM series_and_movies WHERE video_type='season' and series_id='{$Series}' ", PDO::FETCH_ASSOC);
if ($GetSeries->rowCount()) {
    $HtmlOutput .= '<option value="" disabled selected>' . StaticFunctions::lang('95_please-select') . '</option>';
    foreach ($GetSeries as $row) {
        $HtmlOutput .= '<option value="' . $row['id'] . '" >' . StaticFunctions::lang($row['video_name']) . '</option>';
    }
}

if ($HtmlOutput == '') {
    $HtmlOutput .= '<option disabled selected>' . StaticFunctions::lang('96_please-add-season') . '</option>';
}

echo $HtmlOutput;