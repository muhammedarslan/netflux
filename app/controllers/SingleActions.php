<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ProfileID = StaticFunctions::GetProfileId();
$Token = StaticFunctions::post('itemID');
$ItemID = explode('87654', $Token, 2)[1];


$ChekToken = $db->query("SELECT id,series_id,video_type FROM series_and_movies WHERE id = '{$ItemID}' and video_source != '' ")->fetch(PDO::FETCH_ASSOC);

if ($ChekToken) {

    if ($ChekToken['video_type'] == 'episode' || $ChekToken['video_type'] == 'series') {
        $SeriesID = $ChekToken['series_id'];
        $ChekToken['id'] = $SeriesID;
    }


    $CheckUserData = $db->query("SELECT * FROM users_data WHERE user_id = '{$ProfileID}'")->fetch(PDO::FETCH_ASSOC);
    $MyList = json_decode($CheckUserData['my_list'], true);
    $MyLiked = json_decode($CheckUserData['my_liked'], true);
    $MyUnliked = json_decode($CheckUserData['my_unliked'], true);

    $InMyList = false;
    $InMyLike = false;
    $InMyUnlike = false;

    foreach ($MyList as $key => $value) {
        if ($value == $ChekToken['id']) {
            $InMyList = true;
        }
    }

    foreach ($MyLiked as $key => $value) {
        if ($value == $ChekToken['id']) {
            $InMyLike = true;
        }
    }

    foreach ($MyUnliked as $key => $value) {
        if ($value == $ChekToken['id']) {
            $InMyUnlike = true;
        }
    }

    echo StaticFunctions::ApiJson([
        'InList' => $InMyList,
        'InLiked' => $InMyLike,
        'InUnliked' => $InMyUnlike
    ]);
    exit;
}

http_response_code(401);
exit;