<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();

$ID =  StaticFunctions::post('video_id');

$GetVideo = $db->query("SELECT * FROM series_and_movies WHERE id = '{$ID}'")->fetch(PDO::FETCH_ASSOC);
if ($GetVideo) {
    $Images = json_decode($GetVideo['video_images'], true);
    $U = StaticFunctions::post('image');

    foreach ($Images as $key => $value) {
        if ($value == $U) {
            unset($Images[$key]);
        }
    }

    $NewJson = json_encode($Images);
    $query = $db->prepare("UPDATE series_and_movies SET
        video_images = :is
    WHERE id = :id");
    $update = $query->execute(array(
        "is" => $NewJson,
        "id" => $GetVideo['id']
    ));
}

echo $ID;