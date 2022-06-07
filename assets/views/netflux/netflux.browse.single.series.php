<?php

$PageCss = [
    '/assets/netflux/css/swiper.min.css',
    '/assets/netflux/css/slick.min.css'
];
$PageJs = [
    '/assets/netflux/js/swiper.min.js',
    '/assets/netflux/js/slick.min.js',
    '/assets/netflux/js/list.js',
    '/assets/netflux/js/actions.js',
    '/assets/netflux/js/browse.top.js',
    '/assets/netflux/js/single.browse.js'
];

$ProfileID = StaticFunctions::GetProfileId();
$ProfileLevel = $db->query("SELECT profile_level from profiles WHERE id='{$ProfileID}' ")->fetch(PDO::FETCH_ASSOC)['profile_level'];
$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);

require_once StaticFunctions::View('V' . '/classic.header.php');

$SeriesID = $LastVideo['series_id'];

$SeriesVideo = $db->query("SELECT video_name,id,video_translations FROM series_and_movies WHERE id='{$SeriesID}'  ")->fetch(PDO::FETCH_ASSOC);
if (!$SeriesVideo) {
    StaticFunctions::go_home();
}

?>
<div class="detail-modal-wrapper">
    <div class="backdrop"></div>
    <div class="content">
        <div class="image" style="background: url(<?= json_decode($LastVideo['video_images'], true)[1] ?>">
            <div class="name-area">
                <?= StaticFunctions::VideoTranslation($SeriesVideo['video_name'], $SeriesVideo['video_translations'], 'name') ?>
            </div>
            <div class="buttons">
                <a href="<?= PATH ?>watch/87654<?= $LastVideo['id'] . '/' . StaticFunctions::BrowseReferer() ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                        id="Layer_1" x="0px" y="0px" viewBox="0 0 494.148 494.148"
                        style="enable-background:new 0 0 494.148 494.148;" xml:space="preserve">
                        <g>
                            <g>
                                <path fill="#000"
                                    d="M405.284,201.188L130.804,13.28C118.128,4.596,105.356,0,94.74,0C74.216,0,61.52,16.472,61.52,44.044v406.124    c0,27.54,12.68,43.98,33.156,43.98c10.632,0,23.2-4.6,35.904-13.308l274.608-187.904c17.66-12.104,27.44-28.392,27.44-45.884    C432.632,229.572,422.964,213.288,405.284,201.188z" />
                            </g>
                        </g>
                    </svg>
                    <?= Staticfunctions::lang('530_play') ?>
                </a>
                <span id="SingleBrowseAction1" data-token="<?= $LastVideo['video_token'] ?>" onClick="ListAdded1(this);"
                    data-tooltip="<?= Staticfunctions::lang('531_add-to-my') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                        id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;"
                        xml:space="preserve">
                        <g>
                            <g>
                                <path
                                    d="M465.064,207.566l0.028,0H284.436V27.25c0-14.84-12.016-27.248-26.856-27.248h-23.116    c-14.836,0-26.904,12.408-26.904,27.248v180.316H26.908c-14.832,0-26.908,12-26.908,26.844v23.248    c0,14.832,12.072,26.78,26.908,26.78h180.656v180.968c0,14.832,12.064,26.592,26.904,26.592h23.116    c14.84,0,26.856-11.764,26.856-26.592V284.438h180.624c14.84,0,26.936-11.952,26.936-26.78V234.41    C492,219.566,479.904,207.566,465.064,207.566z" />
                            </g>
                        </g>
                    </svg>
                </span>
                <span id="SingleBrowseAction2" data-token="<?= $LastVideo['video_token'] ?>" onClick="ListAdded2(this);"
                    data-tooltip="<?= Staticfunctions::lang('532_remove-from-my') ?>">
                    <svg style="transform: rotate(135deg)" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
                        viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve">
                        <g>
                            <g>
                                <path
                                    d="M465.064,207.566l0.028,0H284.436V27.25c0-14.84-12.016-27.248-26.856-27.248h-23.116    c-14.836,0-26.904,12.408-26.904,27.248v180.316H26.908c-14.832,0-26.908,12-26.908,26.844v23.248    c0,14.832,12.072,26.78,26.908,26.78h180.656v180.968c0,14.832,12.064,26.592,26.904,26.592h23.116    c14.84,0,26.856-11.764,26.856-26.592V284.438h180.624c14.84,0,26.936-11.952,26.936-26.78V234.41    C492,219.566,479.904,207.566,465.064,207.566z" />
                            </g>
                        </g>
                    </svg>
                </span>
                <span id="SingleBrowseAction3" data-token="<?= $LastVideo['video_token'] ?>" onClick="ListLiked1(this);"
                    data-tooltip="<?= Staticfunctions::lang('533_i-love') ?>">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M15.167 8.994h3.394l.068.023c1.56.138 2.867.987 2.867 2.73 0 .275-.046.527-.092.78.367.435.596.986.596 1.72 0 .963-.39 1.52-1.032 1.978.023.183.023.252.023.39 0 .963-.39 1.784-1.009 2.243.023.206.023.275.023.39 0 1.743-1.33 2.591-2.89 2.73L12.21 22c-2.04 0-3.05-.252-4.563-.895-.917-.39-1.353-.527-2.27-.619L4 20.371v-8.234l2.476-1.445 2.27-4.427c0-.046.085-1.552.253-4.52l.871-.389c.092-.069.275-.138.505-.184.664-.206 1.398-.252 2.132 0 1.261.436 2.064 1.537 2.408 3.258.142.829.226 1.695.26 2.564l-.008 2zm-4.42-2.246l-2.758 5.376L6 13.285v5.26c.845.113 1.44.3 2.427.72 1.37.58 2.12.735 3.773.735l4.816-.023c.742-.078.895-.3 1.015-.542.201-.4.201-.876 0-1.425.558-.184.917-.479 1.078-.883.182-.457.182-.966 0-1.528.601-.228.901-.64.901-1.238s-.202-1.038-.608-1.32c.23-.46.26-.892.094-1.294-.168-.404-.298-.627-1.043-.738l-.172-.015h-5.207l.095-2.09c.066-1.448-.009-2.875-.216-4.082-.216-1.084-.582-1.58-1.096-1.758-.259-.09-.546-.086-.876.014-.003.06-.081 1.283-.235 3.67z">
                        </path>
                    </svg>
                </span>
                <span id="SingleBrowseAction4" data-token="<?= $LastVideo['video_token'] ?>" onClick="ListLiked2(this);"
                    data-tooltip="<?= Staticfunctions::lang('534_not-for') ?>">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M8.833 15.006H5.44l-.068-.023c-1.56-.138-2.867-.987-2.867-2.73 0-.275.046-.527.092-.78C2.23 11.038 2 10.487 2 9.753c0-.963.39-1.52 1.032-1.978-.023-.183-.023-.252-.023-.39 0-.963.39-1.784 1.009-2.243-.023-.206-.023-.275-.023-.39 0-1.743 1.33-2.591 2.89-2.73L11.79 2c2.04 0 3.05.252 4.563.895.917.39 1.353.527 2.27.619L20 3.629v8.234l-2.476 1.445-2.27 4.427c0 .046-.085 1.552-.253 4.52l-.871.389c-.092.069-.275.138-.505.184-.664.206-1.398.252-2.132 0-1.261-.436-2.064-1.537-2.408-3.258a19.743 19.743 0 0 1-.26-2.564l.008-2zm4.42 2.246l2.758-5.376L18 10.715v-5.26c-.845-.113-1.44-.3-2.427-.72C14.203 4.156 13.453 4 11.8 4l-4.816.023c-.742.078-.895.3-1.015.542-.201.4-.201.876 0 1.425-.558.184-.917.479-1.078.883-.182.457-.182.966 0 1.528-.601.228-.901.64-.901 1.238s.202 1.038.608 1.32c-.23.46-.26.892-.094 1.294.168.404.298.627 1.043.738l.172.015h5.207l-.095 2.09c-.066 1.448.009 2.875.216 4.082.216 1.084.582 1.58 1.096 1.758.259.09.546.086.876-.014.003-.06.081-1.283.235-3.67z">
                        </path>
                    </svg>
                </span>
            </div>
        </div>
        <div class="details">
            <div class="info-area">
                <div>
                    <div class="badges">
                        <span class="similar">%99 <?= Staticfunctions::lang('66_matching') ?></span>
                        <span class="date"> <?= $LastVideo['video_year'] ?> </span>

                        <?php

                        $VideoLevel = StaticFunctions::VideoAdulthoodLevel($LastVideo['video_level']);
                        if ($VideoLevel != '__noLevel__') {
                            echo '<span class="age">' . $VideoLevel . '</span>';
                        }

                        $SeasonCount = $db->query("SELECT id from series_and_movies WHERE video_type='season' and series_id='{$SeriesID}'", PDO::FETCH_ASSOC)->rowCount();

                        ?>
                        <span class="season"><?= $SeasonCount . ' ' . Staticfunctions::lang('64_season') ?></span>
                    </div>
                    <?php

                    require_once CDIR . '/class.populer.videos.php';
                    $PopularityClass = new NetfluxPopulerItems();
                    $PopularityClass->setDb($db);
                    $PopularityOrder = $PopularityClass->SingleItemPopularity($SeriesID);

                    if ($PopularityOrder > 0) {
                        echo '<div class="top">
                        ' . StaticFunctions::lang('542_this-month-0-number-in-tv', [$PopularityOrder]) . '
                        <div class="icon"></div>
                    </div>';
                    }

                    ?>
                    <p><?= StaticFunctions::TrimText(StaticFunctions::VideoTranslation($LastVideo['video_description'], $LastVideo['video_translations'], 'description'), 1000) ?>
                    </p>
                </div>
                <div class="cast">
                    <p>
                        <span><?= Staticfunctions::lang('543_cast') ?></span>
                        <?php

                        $DataArray = $LastVideo['video_actors'];
                        $DataIds = json_decode($DataArray, true);
                        $ImpLode = implode(',', $DataIds);
                        if ($ImpLode != '') {
                            $DataQuery = $db->query("SELECT * FROM actors WHERE id IN($ImpLode) ", PDO::FETCH_ASSOC);
                            $RowCount = $DataQuery->rowCount();
                            if ($RowCount) {
                                $Counter = 0;
                                foreach ($DataQuery as $row) {
                                    $Counter++;
                                    if ($Counter == $RowCount) {
                                        echo '<a href="' . PATH . 'browse/actor/87654' . $row['id'] . '">' . StaticFunctions::say($row['actor_name']) . '</a>';
                                    } else {
                                        echo '<a href="' . PATH . 'browse/actor/87654' . $row['id'] . '">' . StaticFunctions::say($row['actor_name']) . ',</a>&nbsp;';
                                    }
                                }
                            }
                        }

                        ?>

                    </p>
                    <p>
                        <span><?= Staticfunctions::lang('544_directing') ?></span>
                        <?php

                        $DataArray = $LastVideo['video_directors'];
                        $DataIds = json_decode($DataArray, true);
                        $ImpLode = implode(',', $DataIds);
                        if ($ImpLode != '') {
                            $DataQuery = $db->query("SELECT * FROM directors WHERE id IN($ImpLode) ", PDO::FETCH_ASSOC);
                            $RowCount = $DataQuery->rowCount();
                            if ($RowCount) {
                                $Counter = 0;
                                foreach ($DataQuery as $row) {
                                    $Counter++;
                                    if ($Counter == $RowCount) {
                                        echo '<a href="' . PATH . 'browse/director/87654' . $row['id'] . '">' . StaticFunctions::say($row['director_name']) . '</a>';
                                    } else {
                                        echo '<a href="' . PATH . 'browse/director/87654' . $row['id'] . '">' . StaticFunctions::say($row['director_name']) . ',</a>&nbsp;';
                                    }
                                }
                            }
                        }

                        ?>

                    </p>
                    <p>
                        <span><?= Staticfunctions::lang('545_species') ?></span>
                        <?php

                        $DataArray = $LastVideo['video_categories'];
                        $DataIds = json_decode($DataArray, true);
                        $ImpLode = implode(',', $DataIds);
                        if ($ImpLode != '') {
                            $DataQuery = $db->query("SELECT * FROM genres WHERE id IN($ImpLode) ", PDO::FETCH_ASSOC);
                            $RowCount = $DataQuery->rowCount();
                            if ($RowCount) {
                                $Counter = 0;
                                foreach ($DataQuery as $row) {
                                    $Counter++;
                                    if ($Counter == $RowCount) {
                                        echo '<a href="' . PATH . 'browse/genre/87654' . $row['id'] . '">' . StaticFunctions::GenreTranslation($row['genres_name'], $row['genre_translations']) . '</a>';
                                    } else {
                                        echo '<a href="' . PATH . 'browse/genre/87654' . $row['id'] . '">' . StaticFunctions::GenreTranslation($row['genres_name'], $row['genre_translations']) . ',</a>&nbsp;';
                                    }
                                }
                            }
                        }

                        ?>

                    </p>

                </div>
            </div>
            <div class="parts">
                <div class="head">
                    <span><?= Staticfunctions::lang('552_departments') ?></span>
                    <select id="SeasonSelector" onchange="SeasonChange();">
                        <?php

                        for ($i = 1; $i < ($SeasonCount + 1); $i++) {
                            echo '<option value="' . $i . '" >' . $i . '. ' . Staticfunctions::lang('64_season') . '</option>';
                        }

                        ?>

                    </select>
                </div>
                <div class="items">

                    <?php

                    $CheckUserData = StaticFunctions::MyDataQuery();
                    $MyList = json_decode($CheckUserData['watch_list'], true);

                    Jenssegers\Date\Date::setLocale(mb_strtolower(LANG));

                    $N = 0;
                    $GetSeasons = $db->query("SELECT id,series_id from series_and_movies WHERE series_id='{$SeriesID}' and video_type='season' ", PDO::FETCH_ASSOC);
                    if ($GetSeasons->rowCount()) {
                        foreach ($GetSeasons as $key => $Season) {
                            $N++;
                            $InnerCounter = 0;
                            $GetEpisodes = $db->query("SELECT *  from series_and_movies WHERE video_type='episode' and series_season_id='{$Season['id']}' ", PDO::FETCH_ASSOC);
                            if ($GetEpisodes->rowCount()) {
                                foreach ($GetEpisodes as $key => $Episode) {
                                    $InnerCounter++;
                                    $Display = ($N == 1) ? '' : 'style="display:none;"';

                                    $date = new  Jenssegers\Date\Date('+' . $Episode['video_duration'] . ' second');
                                    $DurationText =  Jenssegers\Date\Date::now()->timespan($date);
                                    $VideoImg = json_decode($Episode['video_images'], true)[0];
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

                                    echo '<a ' . $Display . ' data-season-id=' . $N . ' href="' . PATH . 'watch/87654' . $VideoID . '/' . StaticFunctions::WatchReferer() . '" class="item episode_item">
                        <div class="count">' . $InnerCounter . '</div>
                        <div class="image">
                            <div class="progress">
                                <span style="--item-percentage:' . $WatchedPercentS . '%;"  ></span>
                            </div>
                            <div class="play">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 494.148 494.148"
                                    style="enable-background:new 0 0 494.148 494.148;" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path fill="#fff"
                                                d="M405.284,201.188L130.804,13.28C118.128,4.596,105.356,0,94.74,0C74.216,0,61.52,16.472,61.52,44.044v406.124    c0,27.54,12.68,43.98,33.156,43.98c10.632,0,23.2-4.6,35.904-13.308l274.608-187.904c17.66-12.104,27.44-28.392,27.44-45.884    C432.632,229.572,422.964,213.288,405.284,201.188z" />
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <img src="' . $VideoImg . '"
                                alt="" />
                        </div>
                        <div class="text">
                            <div class="time">' . $DurationText . '.</div>
                            <div class="label">' . StaticFunctions::VideoTranslation($Episode['video_name'], $Episode['video_translations'], 'name') . '</div>
                            <p>' . StaticFunctions::TrimText2(StaticFunctions::VideoTranslation($Episode['video_description'], $Episode['video_translations'], 'description'), 180) . '</p>
                        </div>
                    </a>';
                                }
                            }
                        }
                    }

                    ?>

                </div>
            </div>

            <div id="SimilarVideos"></div>

            <div class="about">
                <div class="hero">
                    <?= Staticfunctions::lang('547_about', [
                        '<strong>' . StaticFunctions::VideoTranslation($SeriesVideo['video_name'], $SeriesVideo['video_translations'], 'name') . '</strong>'
                    ]) ?>
                </div>
                <div class="cast">
                    <p>
                        <span><?= Staticfunctions::lang('550_release') ?></span>
                        <a href="javascript:;"><?= $LastVideo['video_year'] ?></a>
                    </p>
                    <p>
                        <span><?= Staticfunctions::lang('551_adult') ?></span>
                        <?php
                        $VideoLevel = StaticFunctions::VideoAdulthoodLevel($LastVideo['video_level']);
                        if ($VideoLevel != '__noLevel__') {
                            echo '<a href="javascript:;" class="age" >' . $VideoLevel . '</a>';
                        } else {
                            echo '<a href="javascript:;" >' . Staticfunctions::lang('548_general') . '</a>';
                        }
                        ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>



<?php

require_once StaticFunctions::View('V' . '/classic.footer.php');

?>