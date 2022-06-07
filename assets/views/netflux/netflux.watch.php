<?php

$PageCss = [
    '/assets/netflux/css/player.css'
];
$PageJs = [
    '/assets/netflux/js/hls.js',
    '/assets/netflux/js/plyr.cdn.js',
    '/assets/netflux/js/plyr.js'
];

if (isset($_GET['load']) && StaticFunctions::clear($_GET['load']) == 'header') {
    echo '';
    exit;
}


$WatchID = $_Params[0];

if ($WatchID == '') {
    StaticFunctions::go_home();
}

$CheckVideo = $db->query("SELECT * FROM series_and_movies WHERE id = '{$WatchID}'")->fetch(PDO::FETCH_ASSOC);
if (!$CheckVideo) {
    StaticFunctions::go_home();
}

StaticFunctions::checkAdulthoodLevel($CheckVideo['video_level']);

if ($CheckVideo['video_type'] == 'movie') {
    $SingleRow = $CheckVideo;
}

if ($CheckVideo['video_type'] == 'episode') {
    $SingleRow = $CheckVideo;
}

if ($CheckVideo['video_type'] == 'series') {
    $SeriesID = $CheckVideo['id'];
    $GetVideo = $db->query("SELECT * FROM series_and_movies WHERE series_id='{$SeriesID}' and video_type='episode' order by id ASC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
    $SingleRow = $GetVideo;
}

if ($CheckVideo['video_type'] == 'season') {
    $SeriesID = $CheckVideo['series_id'];
    $GetVideo = $db->query("SELECT * FROM series_and_movies WHERE series_id='{$SeriesID}' and video_type='episode' order by id ASC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
    $SingleRow = $GetVideo;
}

$StreamUrl = '';

if ($SingleRow['video_stream_url'] != '') {
    $StreamUrl = $SingleRow['video_stream_url'];
} else {
    $StreamUrl = $SingleRow['video_source'];
}

if ($StreamUrl == '') {
    StaticFunctions::go_home();
}

StaticFunctions::BarbaLoaded($PageCss, $PageJs);

$Referer = PROTOCOL . DOMAIN . PATH . 'browse';
$Jwt = $_Params[1];

try {
    $DecodeHash = \Firebase\JWT\JWT::decode($Jwt, StaticFunctions::JwtKey(), array('HS256'));
} catch (\Throwable $th) {
}

if (isset($DecodeHash->Referer)) {
    $Referer = $DecodeHash->Referer;
}


$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);


?>
<!DOCTYPE html>
<html lang="<?= LANG ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= StaticFunctions::lang('1_netflux') ?></title>
    <link rel="icon" href="<?= PATH ?>assets/media/fav.ico" type="image/x-icon" />
    <link data-rmv='rmv' href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap"
        rel="stylesheet">
    <link data-rmv='rmv' rel="stylesheet" href="<?= PATH ?>assets/netflux/css/bootstrap.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.plyr.io/3.6.3/plyr.css">
    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/main.css">
    <script>
    var InternalAjaxHost = '<?= PROTOCOL . DOMAIN . PATH ?>';
    var AppLang = '<?= LANG ?>';
    </script>
</head>

<body class="watch_body" data-barba="wrapper">

    <div class="PureBlack" style="height:700px;display:block;"></div>


    <main style="display: none;" class="MainContent" data-barba="container" data-barba-easy="<?= 'watch' . LANG ?>">
        <input type="text" value="classic" hidden id="DataHeader" />
        <input type="text" value="<?= $SingleRow['video_token'] ?>" hidden id="VideoID" />
        <style>
        #VideoStream {
            width: 100%;
        }

        .vjs-poster {
            background-size: cover !important;
        }

        video {
            object-fit: cover;
        }

        .vjs-loading-spinner {
            display: none !important;
        }

        .VideoStream-dimensions {
            height: 100% !important;
        }
        </style>

        <div class="loading-container">
            <div class="lds-ring">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="player-area">
            <div class="back">
                <a href="javascript:;" onclick="window.location='<?= $Referer ?>';return false;"
                    style="--go-back:'<?= Staticfunctions::lang('570_turn') ?>';" class="go-back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                        id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;"
                        xml:space="preserve">
                        <g>
                            <g>
                                <path fill="#fff"
                                    d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" />
                            </g>
                        </g>
                    </svg>
                </a>
            </div>
            <div class="video-info">
                <div class="video-info-inner">
                    <div class="info"><?= Staticfunctions::lang('554_now') ?></div>
                    <?php
                    if ($SingleRow['video_type'] == 'episode') {
                        $SeriesName = $db->query("SELECT video_name,video_translations FROM series_and_movies WHERE id='{$SingleRow['series_id']}'")->fetch(PDO::FETCH_ASSOC);
                        echo ' <div class="name">' . StaticFunctions::VideoTranslation($SeriesName['video_name'], $SeriesName['video_translations'], 'name') . '</div>';
                        echo '<span class="season">' . StaticFunctions::VideoTranslation($SingleRow['video_name'], $SingleRow['video_translations'], 'name') . '</span>';
                    } else {
                        echo ' <span class="season">' . StaticFunctions::VideoTranslation($SingleRow['video_name'], $SingleRow['video_translations'], 'name') . '</span>';
                    }

                    ?>
                    <div class="detail">
                        <?php

                        if ($SingleRow['video_type'] != 'movie') {
                            $SeasonID = $SingleRow['series_season_id'];
                            $GetEpisodes = $db->query("SELECT * from series_and_movies  WHERE video_type='episode' and series_season_id='{$SeasonID}' ", PDO::FETCH_ASSOC);
                            $EpisodeOrder = 0;
                            if ($GetEpisodes->rowCount()) {
                                $N = 0;
                                foreach ($GetEpisodes as $key => $season) {
                                    $N++;
                                    if ($season['id'] == $SingleRow['id']) {
                                        $EpisodeOrder = $N;
                                        break;
                                    }
                                }
                            }

                            $GetSeasons = $db->query("SELECT id from series_and_movies WHERE video_type='season' and series_id='{$SingleRow['series_id']}' ", PDO::FETCH_ASSOC);
                            $CurrentSeason = 0;
                            if ($GetSeasons->rowCount()) {
                                $N = 0;
                                foreach ($GetSeasons as $key => $season) {
                                    $N++;
                                    if ($season['id'] == $SingleRow['series_season_id']) {
                                        $CurrentSeason = $N;
                                        break;
                                    }
                                }
                            }

                            if ($EpisodeOrder > 0) echo '<strong>' . Staticfunctions::lang('555_0', [
                                $EpisodeOrder
                            ]) . '</strong>';
                        }

                        ?>
                        <p>
                            <?= StaticFunctions::TrimText2(StaticFunctions::VideoTranslation($SingleRow['video_description'], $SingleRow['video_translations'], 'description'), 1000) ?>
                        </p>

                        <a href="javascript:;" onclick="player.play()">
                            <button type="button" class="billboard-button-play">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M3 22v-20l18 10-18 10z"></path>
                                </svg>
                                <span><?= Staticfunctions::lang('556_go') ?></span>
                            </button>
                        </a>

                    </div>
                </div>
            </div>
            <?php
            if ($SingleRow['video_type'] == 'episode') {
                $SeriesName = $db->query("SELECT video_name,video_translations FROM series_and_movies WHERE id='{$SingleRow['series_id']}'")->fetch(PDO::FETCH_ASSOC);
                echo '<div class="js-info-div" data-name="' . StaticFunctions::VideoTranslation($SeriesName['video_name'], $SeriesName['video_translations'], 'name') . '"></div>';
            } else {
                echo '<div class="js-info-div" data-name="' . StaticFunctions::VideoTranslation($SingleRow['video_name'], $SingleRow['video_translations'], 'name') . '"></div>';
            }

            ?>
            <?php

            echo '<input type="text" value="m3u8" hidden id="StreamType" />';
            echo '<video id="player" controls data-poster="' . json_decode($SingleRow['video_images'], true)[1] . '">
            <source type="application/x-mpegURL"><source src="' . $StreamUrl . '" type="video/mp4" />
        </video>';

            ?>

            <!-- Captions are optional -->
            <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
            </video>
        </div>


        <div id="PlayerControllers" style="display: none;">

            <div class="plyr__controls plyr__controls--top">
                <div class="plyr__progress">
                    <input data-plyr="seek" type="range" min="0" max="100" step="0.01" value="0" aria-label="Seek">
                    <progress class="plyr__progress__buffer" min="0" max="100" value="0">% buffered</progress>
                    <span role="tooltip" class="plyr__tooltip">00:00</span>
                </div>
                <div class="plyr__time plyr__time--current" aria-label="Current time">00:00</div>
                <div class="plyr__time plyr__time--duration" aria-label="Duration">00:00</div>
            </div>
            <div class="plyr__controls">
                <button type="button" class="plyr__control" aria-label="Başlat, {title}" data-plyr="play">
                    <svg class="icon--pressed" role="presentation">
                        <use xlink:href="#plyr-pause"></use>
                    </svg>
                    <svg id="nfplayerPlay" class="icon--not-pressed" role="presentation" viewBox="0 0 28 28">
                        <polygon points="8 22 8 6 22.0043763 14"></polygon>
                    </svg>
                    <span class="label--pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('557_stop') ?></span>
                    <span class="label--not-pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('558_start') ?></span>
                </button>
                <button type="button" class="plyr__control" data-plyr="rewind">
                    <svg id="nfplayerBackTen" viewBox="0 0 28 28">
                        <g stroke="none" stroke-width="1" fill="none">
                            <path
                                d="M21.9992616,8.99804242 C23.2555293,10.6696987 24,12.7479091 24,15 C24,20.5228475 19.5228475,25 14,25 C8.4771525,25 4,20.5228475 4,15 C4,9.4771525 8.4771525,5 14,5 L16,5"
                                stroke="white" stroke-width="2"
                                transform="translate(14.000000, 15.000000) scale(-1, 1) translate(-14.000000, -15.000000) ">
                            </path>
                            <polyline stroke="white" stroke-width="2" points="15.5 1.5 12 4.92749023 15.5 8.5">
                            </polyline>
                            <polyline stroke="white" stroke-width="2" points="11 1.5 7.5 5 11 8.5"></polyline><text
                                font-size="10" font-weight="400" letter-spacing="-0.3" fill="white">
                                <tspan x="8" y="19">1</tspan>
                                <tspan x="13.82" y="19">0</tspan>
                            </text>
                        </g>
                    </svg>
                    <span class="plyr__tooltip" role="tooltip"><?= Staticfunctions::lang('559_rewind-seectime') ?></span>
                </button>
                <button type="button" class="plyr__control" data-plyr="fast-forward">
                    <svg id="nfplayerFastForward" viewBox="0 0 28 28">
                        <g stroke="none" stroke-width="1" fill="none">
                            <g
                                transform="translate(14.000000, 13.000000) scale(-1, 1) translate(-14.000000, -13.000000) translate(4.000000, 1.000000)">
                                <path
                                    d="M17.9992616,7.99804242 C19.2555293,9.66969874 20,11.7479091 20,14 C20,19.5228475 15.5228475,24 10,24 C4.4771525,24 0,19.5228475 0,14 C0,8.4771525 4.4771525,4 10,4 L12,4"
                                    stroke="white" stroke-width="2"
                                    transform="translate(10.000000, 14.000000) scale(-1, 1) translate(-10.000000, -14.000000) ">
                                </path>
                                <polyline stroke="white" stroke-width="2" points="11.5 0.5 8 3.92749023 11.5 7.5">
                                </polyline>
                                <polyline stroke="white" stroke-width="2" points="7 0.5 3.5 4 7 7.5"></polyline>
                            </g><text font-size="10" font-weight="400" letter-spacing="-0.3" fill="white">
                                <tspan x="8" y="19">10</tspan>
                            </text>
                        </g>
                    </svg>
                    <span class="plyr__tooltip" role="tooltip"><?= Staticfunctions::lang('560_move-forward-seectime') ?></span>
                </button>
                <button type="button" class="plyr__control" aria-label="Mute" data-plyr="mute">
                    <svg class="icon--pressed" role="presentation">
                        <use xlink:href="#plyr-muted"></use>
                    </svg>
                    <svg class="icon--not-pressed" id="volumeMax" viewBox="0 0 28 28">
                        <path
                            d="M13,22 L7,18 L3,18 L3,10 L7,10 L13,6 L13,22 Z M16.7437869,18.3889482 L15.3295734,16.9747347 C16.9546583,15.3496497 16.9546583,12.7148664 15.3295734,11.0897815 L16.7437869,9.6755679 C19.1499205,12.0817014 19.1499205,15.9828147 16.7437869,18.3889482 Z M19.2137399,20.2137399 L17.7995264,18.7995264 C20.4324159,16.1666368 20.4324159,11.8978793 17.7995264,9.26498977 L19.2137399,7.8507762 C22.6276781,11.2647144 22.6276781,16.7998018 19.2137399,20.2137399 Z M21.6836929,22.0385316 L20.2694793,20.6243181 C23.9101736,16.9836239 23.9101736,11.0808923 20.2694793,7.44019807 L21.6836929,6.02598451 C26.1054357,10.4477273 26.1054357,17.6167888 21.6836929,22.0385316 Z">
                        </path>
                    </svg>
                    <span class="label--pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('561_volume') ?></span>
                    <span class="label--not-pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('562_quiet') ?></span>
                </button>
                <div class="plyr__volume">
                    <input data-plyr="volume" type="range" min="0" max="1" step="0.05" value="1" autocomplete="off"
                        aria-label="Ses">
                </div>
                <div class="plyr__info">
                    <div class="name js-name-area-video">
                        -
                    </div>
                    <?php

                    if ($SingleRow['video_type'] != 'movie') {
                    ?>
                    <div class="info js-info-area-video"><?= Staticfunctions::lang('S{0}:B{1} - {2}', [
                                                                    $CurrentSeason, $EpisodeOrder, StaticFunctions::VideoTranslation($SingleRow['video_name'], $SingleRow['video_translations'], 'name')
                                                                ]) ?></div>
                    <?php
                    }

                    ?>
                </div>
                <button type="button" class="plyr__control" data-plyr="captions">
                    <svg class="icon--pressed" role="presentation">
                        <use xlink:href="#plyr-captions-on"></use>
                    </svg>
                    <svg class="icon--not-pressed" role="presentation">
                        <use xlink:href="#plyr-captions-off"></use>
                    </svg>
                    <span class="label--pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('563_turn-off') ?></span>
                    <span class="label--not-pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('564_open') ?></span>
                </button>
                <?php

                if ($SingleRow['video_type'] != 'movie') {

                    $NextEpisode = $db->query("SELECT * from series_and_movies WHERE series_id='{$SingleRow['series_id']}' and video_type='episode' and id > '{$SingleRow['id']}' order by id ASC ")->fetch(PDO::FETCH_ASSOC);
                    if ($NextEpisode) {
                        if ($NextEpisode['series_season_id'] == $SingleRow['series_season_id']) {
                            $NextEpisodeCounter = $EpisodeOrder + 1;
                        } else {
                            $NextEpisodeCounter = 1;
                        }
                ?>
                <a href="javascript:;"
                    onclick="window.location='<?= PATH . 'watch/87654' . $NextEpisode['id'] . '/' . $Jwt ?>';">
                    <button type="button" class="plyr__control" data-plyr="next-part">
                        <div class="player-dropdown">
                            <div class="hero"><?= Staticfunctions::lang('569_next') ?></div>
                            <div class="item">
                                <div class="detail">
                                    <div class="image">
                                        <img src="<?= json_decode($NextEpisode['video_images'], true)[0] ?>" alt="" />
                                    </div>
                                    <div class="label"><?= Staticfunctions::lang('555_0', [
                                                                    $NextEpisodeCounter
                                                                ]) ?></div>
                                    <?= StaticFunctions::TrimText2(StaticFunctions::VideoTranslation($NextEpisode['video_description'], $NextEpisode['video_translations'], 'description'), 120) ?>
                                </div>
                            </div>
                        </div>
                        <svg id="nfplayerNextEpisode" viewBox="0 0 28 28">
                            <g transform="translate(6, 6)">
                                <path d="M0,16 L0,0 L14,8 L0,16 Z M14,16 L14,0 L16,0 L16,16 L14,16 Z"></path>
                            </g>
                        </svg>
                        <span class="label--not-pressed plyr__tooltip"
                            role="tooltip"><?= Staticfunctions::lang('569_next') ?></span>
                    </button>
                </a>
                <?php
                    }


                    if ($EpisodeOrder > 0) {

                    ?>
                <button type="button" class="plyr__control" data-plyr="parts">
                    <div class="player-dropdown">
                        <div class="hero"><?= Staticfunctions::lang('571_0', [
                                                        $CurrentSeason
                                                    ]) ?></div>
                        <div class="parts-area">
                            <?php


                                    $N = $EpisodeOrder - 1;
                                    $SeasonID = $SingleRow['series_season_id'];
                                    $GetEpisodes = $db->query("SELECT * from series_and_movies  WHERE video_type='episode' and series_season_id='{$SeasonID}' and ( id='{$SingleRow['id']}' or id > '{$SingleRow['id']}' ) order by id ASC LIMIT 4 ", PDO::FETCH_ASSOC);

                                    if ($GetEpisodes->rowCount()) {
                                        foreach ($GetEpisodes as $key => $Episode) {
                                            $N++;

                                            $WatchedPercentS = 0;
                                            $VideoDuration = $Episode['video_duration'];
                                            $VideoID = $Episode['id'];

                                            if (isset($MyList[$VideoID])) {

                                                $WatchedDuration = ceil($MyList[$VideoID]);
                                                $WatchedPercent = ceil($WatchedDuration / $VideoDuration * 100);
                                                if ($WatchedPercent < 5) $WatchedPercent = 5;
                                                $WatchedPercentS = $WatchedPercent;
                                            } else {
                                                $WatchedPercentS = 0;
                                            }

                                            if ($Episode['id'] == $SingleRow['id']) {
                                                echo '<div class="item">
                                <div class="label">
                                    ' . StaticFunctions::VideoTranslation($Episode['video_name'], $Episode['video_translations'], 'name') . '
                                    <div class="progress"><span style="--item-percentage:' . $WatchedPercentS . '%;" ></span></div>
                                </div>
                                <div class="detail">
                                    <div class="image">
                                        <img src="' . json_decode($Episode['video_images'], true)[0] . '"
                                            alt="" />
                                    </div>
                                    ' . StaticFunctions::TrimText2(StaticFunctions::VideoTranslation($Episode['video_description'], $Episode['video_translations'], 'description'), 120) . '
                                </div>
                            </div>';
                                            } else {
                                                echo '<div onclick="window.location=\'' . PATH . 'watch/87654' . $Episode['id'] . '/' . $Jwt . '\';" class="item">
                                <div class="label">
                                   ' . StaticFunctions::VideoTranslation($Episode['video_name'], $Episode['video_translations'], 'name') . '
                                    <div class="progress"><span style="--item-percentage:' . $WatchedPercentS . '%;" ></span></div>
                                </div>
                            </div>';
                                            }
                                        }
                                    }
                                    ?>


                        </div>
                    </div>
                    <svg id="nfplayerEpisodes" viewBox="0 0 28 28">
                        <path
                            d="M27,7.25 L27,14 L24.7142857,14 L24.7142857,7.25 L11,7.25 L11,5 L27,5 L27,7.25 Z M23,11.2222222 L23,19 L20.7333333,19 L20.7333333,11.2222222 L6,11.2222222 L6,9 L23,9 L23,11.2222222 Z M1,13 L19,13 L19,24 L1,24 L1,13 Z">
                        </path>
                    </svg>
                    <span class="label--not-pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('568_other') ?></span>
                </button>
                <?php

                    }
                }


                ?>

                <button type="button" class="plyr__control" data-plyr="subttitle-area">
                    <div class="player-dropdown">
                        <div class="hero"><?= Staticfunctions::lang('565_subtitles') ?></div>
                        <ul>
                            <li>
                                <a href="javascript:;">
                                    Türkçe
                                </a>
                            </li>

                        </ul>
                    </div>
                    <svg id="nfplayerSubtitles" viewBox="0 0 28 28">
                        <g transform="translate(1, 6)">
                            <path
                                d="M24,14 L24,19 L19,14 L0,14 L0,0 L26,0 L26,14 L24,14 Z M2,6 L2,8 L7,8 L7,6 L2,6 Z M9,6 L9,8 L17,8 L17,6 L9,6 Z M19,6 L19,8 L24,8 L24,6 L19,6 Z M2,10 L2,12 L10,12 L10,10 L2,10 Z M12,10 L12,12 L17,12 L17,10 L12,10 Z">
                            </path>
                        </g>
                    </svg>
                    <span class="label--not-pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('565_subtitles') ?></span>
                </button>
                <button type="button" class="plyr__control" data-plyr="fullscreen">
                    <svg class="icon--pressed" role="presentation">
                        <use xlink:href="#plyr-exit-fullscreen"></use>
                    </svg>
                    <svg class="icon--not-pressed" id="nfplayerFullscreen" viewBox="0 0 28 28">
                        <g transform="translate(2, 6)">
                            <polygon points="8 0 6 0 5.04614258 0 0 0 0 5 2 5 2 2 8 2"></polygon>
                            <polygon transform="translate(4, 13.5) scale(1, -1) translate(-4, -13.5) "
                                points="8 11 6 11 5.04614258 11 0 11 0 16 2 16 2 13 8 13"></polygon>
                            <polygon transform="translate(20, 2.5) scale(-1, 1) translate(-20, -2.5) "
                                points="24 0 22 0 21.0461426 0 16 0 16 5 18 5 18 2 24 2"></polygon>
                            <polygon transform="translate(20, 13.5) scale(-1, -1) translate(-20, -13.5) "
                                points="24 11 22 11 21.0461426 11 16 11 16 16 18 16 18 13 24 13"></polygon>
                        </g>
                    </svg>
                    <span class="label--pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('566_exit-full') ?></span>
                    <span class="label--not-pressed plyr__tooltip"
                        role="tooltip"><?= Staticfunctions::lang('567_switch-to-full') ?></span>
                </button>
            </div>

        </div>


    </main>

    <script src="<?= PATH ?>assets/netflux/js/jquery-3.5.1.min.js" crossorigin="anonymous">
    </script>
    <script src="<?= PATH ?>assets/netflux/js/lazyload.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/bootstrap-validate.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/barba.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/topbar.min.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/core.js"></script>
</body>

</html>
