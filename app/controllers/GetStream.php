<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$ID = StaticFunctions::post('id');

$GetData = $db->query("SELECT * FROM series_and_movies WHERE id = '{$ID}'")->fetch(PDO::FETCH_ASSOC);

$Video  = $GetData['video_source'];
$Stream = $GetData['video_stream_url'];

if ($Stream == '') {
    if (strstr($Video, '.m3u8')) {
        $Stream = $Video;
    }
}



echo StaticFunctions::ApiJson([
    'VideoID'   => $ID,
    'VideoSource' => $Video,
    'StreamSource' => $Stream
]);