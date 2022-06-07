<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ListItems = [];

$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);
$MyWatch = json_decode($CheckUserData['watch_list'], true);


if (is_array($MyWatch)) {
    foreach ($MyWatch as $key => $SingleID) {
        $SingleItem = $db->query("SELECT * FROM series_and_movies WHERE id = '{$key}' and video_source != '' ")->fetch(PDO::FETCH_ASSOC);
        if ($SingleItem) {
            array_push($ListItems, $SingleItem);
        }
    }
}

require_once CDIR . '/class.browse.list.php';

$ListCreator = new NetfluxList();
$ListCreator->setDb($db);
$ListCreator->setProfileList($MyList);
$ListCreator->setProfileWatched($MyWatch);
$ListCreator->setRows($ListItems);

$GetResponseJson = $ListCreator->responseJson();
echo $GetResponseJson;