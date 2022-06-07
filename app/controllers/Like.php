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
    $MyLiked = json_decode($CheckUserData['my_liked'], true);
    $MyUnliked = json_decode($CheckUserData['my_unliked'], true);

    foreach ($MyUnliked as $key => $value) {
        if ($value == $ChekToken['id']) {
            unset($MyUnliked[$key]);
        }
    }

    $IsLiked = false;
    foreach ($MyLiked as $key => $value) {
        if ($value == $ChekToken['id']) {
            $IsLiked = true;
            unset($MyLiked[$key]);
            break;
        }
    }

    if ($IsLiked == false) {
        array_push($MyLiked, $ChekToken['id']);
    }

    $LikedArray = array_unique($MyLiked);
    $UnlikedArray = array_unique($MyUnliked);

    require_once CDIR . '/class.populer.videos.php';
    $Popularity = new NetfluxPopulerItems();
    $Popularity->setDb($db);
    $Popularity->setProfileID($ProfileID);
    $Popularity->setVideoID($ChekToken['id']);
    $Popularity->setVideoType(($ChekToken['video_type'] == 'movie') ? 'movie' : 'series');
    $Popularity->Popular('like');

    $UpdateList = $db->prepare("UPDATE users_data SET
        my_liked = :nis1,
        my_unliked = :nis2
        WHERE id = :ni");
    $update = $UpdateList->execute(array(
        "nis1" => json_encode($LikedArray),
        "nis2" => json_encode($UnlikedArray),
        "ni" => $CheckUserData['id']
    ));

    $Liked = false;
    $UnLiked = false;
    foreach ($LikedArray as $key => $value) {
        if ($value == $ChekToken['id']) {
            $Liked = true;
            break;
        }
    }

    $IsLiked = true;
    foreach ($UnlikedArray as $key => $value) {
        if ($value == $ChekToken['id']) {
            $UnLiked = true;
            break;
        }
    }

    echo StaticFunctions::ApiJson([
        'Liked' => $Liked,
        'Unliked' => $UnLiked
    ]);
    exit;
}

echo StaticFunctions::ApiJson([
    'Liked' => false,
    'Unliked' => false
]);