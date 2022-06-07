    <?php

    $ItemID = $_Params[0];

    if ($ItemID == '') {
        StaticFunctions::go_home();
    }


    $LastVideo = $db->query("SELECT * FROM series_and_movies WHERE id='{$ItemID}' order by id DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
    if (!$LastVideo) {
        StaticFunctions::go_home();
    }

    StaticFunctions::checkAdulthoodLevel($LastVideo['video_level']);

    if ($LastVideo['video_type'] == 'movie') {
        require_once StaticFunctions::View('V' . '/netflux.browse.single.movie.php');
    } else if ($LastVideo['video_type'] != 'episode') {

        if ($LastVideo['video_type'] == 'series') {
            $SeriesID = $LastVideo['id'];
        } else {
            $SeriesID = $LastVideo['series_id'];
        }

        $LastVideo = $db->query("SELECT * FROM series_and_movies WHERE series_id='{$SeriesID}' and video_type='episode' order by id ASC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
        require_once StaticFunctions::View('V' . '/netflux.browse.single.series.php');
    } else {
        require_once StaticFunctions::View('V' . '/netflux.browse.single.series.php');
    }


    ?>