<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ListItems = [];

$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);
$MyWatch = json_decode($CheckUserData['watch_list'], true);
$SeasonID = StaticFunctions::post('data');

if ($SeasonID == '') {
    http_response_code(401);
    exit;
}


$SeasonEpisodes = $db->query("SELECT * FROM series_and_movies WHERE series_season_id='{$SeasonID}' and video_type='episode' and video_source != ''  order by id ASC LIMIT 18 ", PDO::FETCH_ASSOC);
if ($SeasonEpisodes->rowCount()) {
    foreach ($SeasonEpisodes as $row) {
        array_push($ListItems, $row);
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