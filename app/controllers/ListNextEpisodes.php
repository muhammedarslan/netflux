<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ListItems = [];

$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);
$MyWatch = json_decode($CheckUserData['watch_list'], true);
$EpisodeID = StaticFunctions::post('data');

if ($EpisodeID == '') {
    http_response_code(401);
    exit;
}

$SeriesIDQ = $db->query("SELECT series_id FROM series_and_movies WHERE id = '{$EpisodeID}'")->fetch(PDO::FETCH_ASSOC);
$SeriesID = $SeriesIDQ['series_id'];

$NextEpisodes = $db->query("SELECT * FROM series_and_movies WHERE series_id='{$SeriesID}' and video_type='episode' and video_source != '' and id > $EpisodeID order by id ASC LIMIT 6 ", PDO::FETCH_ASSOC);
if ($NextEpisodes->rowCount()) {
    foreach ($NextEpisodes as $row) {
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