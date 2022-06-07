<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$ListItems2 = [];

$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);
$MyWatch = json_decode($CheckUserData['watch_list'], true);

$SearchData = StaticFunctions::post('data');

if ($SearchData == '') {
    http_response_code(401);
    exit;
}

$Search = $db->query("SELECT *,series_and_movies.id FROM series_and_movies
    INNER JOIN genres
    INNER JOIN actors
    INNER JOIN directors
     WHERE 
     video_type = 'movie' and
    (
    (series_and_movies.video_name) LIKE  CONCAT('%','{$SearchData}','%') or
    (series_and_movies.video_description) LIKE  CONCAT('%','{$SearchData}','%') or
    (series_and_movies.video_translations) LIKE  CONCAT('%','{$SearchData}','%') or
    ((genres.genres_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_categories LIKE CONCAT('%', genres.id , '%') ) or
    ((actors.actor_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_actors LIKE CONCAT('%', actors.id , '%') ) or
    ((directors.director_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_directors LIKE CONCAT('%', directors.id , '%') )
    ) LIMIT 20
    ", PDO::FETCH_ASSOC);


if ($Search->rowCount()) {
    foreach ($Search as $row) {
        array_push($ListItems2, $row);
    }
}

$Search = $db->query("SELECT *,series_and_movies.id FROM series_and_movies
    INNER JOIN genres
    INNER JOIN actors
    INNER JOIN directors
     WHERE 
     video_type = 'series' and
    (
    (series_and_movies.video_name) LIKE  CONCAT('%','{$SearchData}','%') or
    (series_and_movies.video_description) LIKE  CONCAT('%','{$SearchData}','%') or
    (series_and_movies.video_translations) LIKE  CONCAT('%','{$SearchData}','%') or
    ((genres.genres_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_categories LIKE CONCAT('%', genres.id , '%') ) or
    ((actors.actor_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_actors LIKE CONCAT('%', actors.id , '%') ) or
    ((directors.director_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_directors LIKE CONCAT('%', directors.id , '%') )
    ) LIMIT 20
    ", PDO::FETCH_ASSOC);

$Search = $db->query("SELECT *,series_and_movies.id FROM series_and_movies
    INNER JOIN genres
    INNER JOIN actors
    INNER JOIN directors
     WHERE 
     video_type = 'episode' and
    (
    (series_and_movies.video_name) LIKE  CONCAT('%','{$SearchData}','%') or
    (series_and_movies.video_description) LIKE  CONCAT('%','{$SearchData}','%') or
    (series_and_movies.video_translations) LIKE  CONCAT('%','{$SearchData}','%') or
    ((genres.genres_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_categories LIKE CONCAT('%', genres.id , '%') ) or
    ((actors.actor_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_actors LIKE CONCAT('%', actors.id , '%') ) or
    ((directors.director_name) LIKE  CONCAT('%','{$SearchData}','%') and series_and_movies.video_directors LIKE CONCAT('%', directors.id , '%') )
    ) LIMIT 20
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