<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();
$ProfileID = StaticFunctions::GetProfileId();
$VideoID = StaticFunctions::post('videoID');
$CurrentTime = StaticFunctions::post('currentTime');


$ChekToken = $db->query("SELECT * FROM series_and_movies WHERE video_token = '{$VideoID}' and video_source != '' ")->fetch(PDO::FETCH_ASSOC);

if ($ChekToken) {

    $CheckUserData = $db->query("SELECT * FROM users_data WHERE user_id = '{$ProfileID}'")->fetch(PDO::FETCH_ASSOC);
    $MyList = json_decode($CheckUserData['watch_list'], true);


    $MyList[$ChekToken['id']] = $CurrentTime;

    $Arr = array_unique($MyList);

    $UpdateList = $db->prepare("UPDATE users_data SET
        watch_list = :nis
        WHERE id = :ni");
    $update = $UpdateList->execute(array(
        "nis" => json_encode($Arr),
        "ni" => $CheckUserData['id']
    ));
}