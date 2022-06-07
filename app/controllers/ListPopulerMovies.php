<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ListItems2 = [];
$ListItems3 = [];

$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);
$MyWatch = json_decode($CheckUserData['watch_list'], true);

require_once CDIR . '/class.populer.videos.php';
$Popular = new NetfluxPopulerItems();
$Popular->setDb($db);
$ListItems3 = $Popular->GetPopulerItems('movie');

if (is_array($ListItems3)) {
    foreach ($ListItems3 as $key => $SingleID) {
        $SingleItem = $db->query("SELECT * FROM series_and_movies WHERE id='{$SingleID}' ")->fetch(PDO::FETCH_ASSOC);
        if ($SingleItem) {
            array_push($ListItems2, $SingleItem);
        }
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