<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ProfileID = StaticFunctions::GetProfileId();
$Token = StaticFunctions::post('tkn');

$ChekToken = $db->query("SELECT id,series_id,video_type FROM series_and_movies WHERE video_token = '{$Token}' and video_source != '' ")->fetch(PDO::FETCH_ASSOC);

if ($ChekToken) {

    if ($ChekToken['video_type'] == 'episode' || $ChekToken['video_type'] == 'series') {
        $SeriesID = $ChekToken['series_id'];
        $ChekToken['id'] = $SeriesID;
    }

    $CheckUserData = $db->query("SELECT * FROM users_data WHERE user_id = '{$ProfileID}'")->fetch(PDO::FETCH_ASSOC);
    $MyList = json_decode($CheckUserData['my_list'], true);

    foreach ($MyList as $key => $value) {
        if ($value == $ChekToken['id']) {
            unset($MyList[$key]);
        }
    }

    $Arr = array_unique($MyList);

    $UpdateList = $db->prepare("UPDATE users_data SET
        my_list = :nis
        WHERE id = :ni");
    $update = $UpdateList->execute(array(
        "nis" => json_encode($Arr),
        "ni" => $CheckUserData['id']
    ));
}

echo StaticFunctions::ApiJson([
    'process' => 'success'
]);