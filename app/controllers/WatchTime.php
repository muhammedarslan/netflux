<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();
$VideoID = StaticFunctions::post('videoID');
$CurrentTime = 0;

$ChekToken = $db->query("SELECT * FROM series_and_movies WHERE video_token = '{$VideoID}' and video_source != '' ")->fetch(PDO::FETCH_ASSOC);

if ($ChekToken) {

    $CheckUserData = StaticFunctions::MyDataQuery();
    $MyList = json_decode($CheckUserData['watch_list'], true);

    if (isset($MyList[$ChekToken['id']])) {
        $CurrentTime = $MyList[$ChekToken['id']];
    }
}

echo StaticFunctions::ApiJson([
    'currentTime' => (float) $CurrentTime
]);