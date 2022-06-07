<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ListItems2 = [];

$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);
$MyWatch = json_decode($CheckUserData['watch_list'], true);

$CatID = StaticFunctions::post('data');

if ($CatID == '') {
    http_response_code(401);
    exit;
}

$Search = $db->query("SELECT * FROM series_and_movies
    WHERE video_type='movie' and video_categories LIKE '%\"{$CatID}\"%' LIMIT 9
    ", PDO::FETCH_ASSOC);
if ($Search->rowCount()) {
    foreach ($Search as $row) {
        array_push($ListItems2, $row);
    }
}

$Search = $db->query("SELECT * FROM series_and_movies
    WHERE video_type='series' and video_categories LIKE '%\"{$CatID}\"%' LIMIT 9
    ", PDO::FETCH_ASSOC);
if ($Search->rowCount()) {
    foreach ($Search as $row) {
        array_push($ListItems2, $row);
    }
}

require_once CDIR . '/class.browse.list.php';

$ListCreator = new NetfluxList();
$ListCreator->setDb($db);
$ListCreator->setProfileList($MyList);
$ListCreator->setProfileWatched($MyWatch);
$ListCreator->setRows($ListItems2);

$GetResponseJson = $ListCreator->responseJson();
echo $GetResponseJson;