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
        '/assets/netflux/js/browse.top.js'
    ];


    $ID = StaticFunctions::clear($_Params[0]);
    $SingleGenre = $db->query("SELECT * FROM genres WHERE id = '{$ID}'")->fetch(PDO::FETCH_ASSOC);
    if (!$SingleGenre) {
        StaticFunctions::NoBarba();
        StaticFunctions::go_home();
    }

    $ItemCount = $db->query("SELECT id from series_and_movies WHERE video_type='movie' and video_categories LIKE '%\"" . $ID . "\"%' and video_source != '' ", PDO::FETCH_ASSOC)->rowCount();
    if ($ItemCount < 1) {
        StaticFunctions::NoBarba();
        StaticFunctions::go_home();
    }

    require_once StaticFunctions::View('V' . '/classic.header.php');


    $ProfileID = StaticFunctions::GetProfileId();
    $ProfileLevel = $db->query("SELECT profile_level from profiles WHERE id='{$ProfileID}' ")->fetch(PDO::FETCH_ASSOC)['profile_level'];

    $LastVideo = $db->query("SELECT * FROM series_and_movies WHERE '{$ProfileLevel}' <= video_level and video_type='movie' and video_categories LIKE '%\"" . $ID . "\"%' order by Rand() DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
    if (!$LastVideo) {
        echo '<style>
        .top_first_section {
            display: none;
        }
        .entity-showcase{
            margin-top:200px;
        }
            </style>';
    }

    ?>
    <style>
.list_videos {
    height: 240px;
}

.category-selector .dropdown ul {
    flex: 0 0 120px;
    max-width: 120px;
}

.list-carousel {
    z-index: 5;
}

.list-carousel:hover {
    z-index: 7;
}
    </style>
    <section class="border-0">


        <div style="position: absolute;
    top: 65px;
    left: 60px;
    z-index: 1;
    font-size: 38px;
    font-weight: 700;
    white-space: nowrap;" class="carousel-title">
            <?= StaticFunctions::lang('108_movies') ?>
        </div>
        <div class="category-selector">
            <div class="toggle">
                <?= StaticFunctions::GenreTranslation($SingleGenre['genres_name'], $SingleGenre['genre_translations']) ?>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                    id="Capa_1" x="0px" y="0px" width="292.362px" height="292.362px" viewBox="0 0 292.362 292.362"
                    style="enable-background:new 0 0 292.362 292.362;" xml:space="preserve">
                    <g>
                        <path fill="#fff"
                            d="M286.935,69.377c-3.614-3.617-7.898-5.424-12.848-5.424H18.274c-4.952,0-9.233,1.807-12.85,5.424   C1.807,72.998,0,77.279,0,82.228c0,4.948,1.807,9.229,5.424,12.847l127.907,127.907c3.621,3.617,7.902,5.428,12.85,5.428   s9.233-1.811,12.847-5.428L286.935,95.074c3.613-3.617,5.427-7.898,5.427-12.847C292.362,77.279,290.548,72.998,286.935,69.377z" />
                    </g>
                </svg>
            </div>
            <div class="dropdown">
                <?php

                $GenresArray = [];
                $GetGenres = $db->query("SELECT * from genres");
                if ($GetGenres->rowCount()) {
                    foreach ($GetGenres as $key => $Genre) {
                        $GenreID = $Genre['id'];
                        $ItemCount = $db->query("SELECT id from series_and_movies WHERE '{$ProfileLevel}' <= video_level and video_type='movie' and video_categories LIKE '%\"" . $GenreID . "\"%' and video_source != '' ", PDO::FETCH_ASSOC)->rowCount();
                        if ($ItemCount > 0) {
                            array_push($GenresArray, [
                                'genreID' => $Genre['id'],
                                'genreName' => StaticFunctions::GenreTranslation($Genre['genres_name'], $Genre['genre_translations'])
                            ]);
                        }
                    }
                }

                $N = 0;
                $GenresObject = [];
                foreach ($GenresArray as $key => $single) {
                    if (!isset($GenresObject[$N])) $GenresObject[$N] = [];
                    if (count($GenresObject[$N]) > 3) {
                        $N++;
                    }
                    if (!isset($GenresObject[$N])) $GenresObject[$N] = [];
                    array_push($GenresObject[$N], $single);
                }

                foreach ($GenresObject as $key => $object) {
                    echo '<ul>';

                    foreach ($object as $key => $single) {
                        echo '<li>
                        <a href="' . PATH . 'browse/movies/87654' . $single['genreID'] . '">' . $single['genreName'] . '</a>
                    </li>';
                    }

                    echo '</ul>';
                }

                ?>
            </div>
        </div>

        <div class="position-relative d-flex align-items-center main-billboard">
            <img id="TopBrowseImg" style="width: 100%;max-height:820px;"
                src="<?= json_decode(@$LastVideo['video_images'], true)[1] ?>">
            <video id="TopBrowseVideo" style="display: none;" loop muted width="100%" class="vm_vd" controls>
                <source src="<?= @$LastVideo['video_short_source'] ?>" type="video/mp4">
                <img src="<?= json_decode(@$LastVideo['video_images'], true)[1] ?>">
            </video>
            <div class="billboard-background"></div>
            <div class="carousel-background"></div>
            <div class="position-absolute billboard-content">
                <div>
                    <!-- <h1 class="mb-3">The Wrong Missy</h1> -->
                    <?php
                    /*
                    
 <img style="max-width: 650px;max-height:230px;" class="entity-logo mb-4"
                        src="<?= @$LastVideo['video_main_image'] ?>"
                    title="<?= StaticFunctions::lang(@$LastVideo['video_name']) ?>"
                    alt="<?= StaticFunctions::lang(@$LastVideo['video_name']) ?>">
                    */
                    ?>

                    <span
                        style="font-family: Helvetica;
    color: #ffffff;
    font-weight: 600;
    font-size: 60px;
    line-height: 80px;"><?= StaticFunctions::VideoTranslation(@$LastVideo['video_name'], @$LastVideo['video_translations'], 'name') ?></span>

                    <h2 class="mb-4">
                        <?= StaticFunctions::TrimText(StaticFunctions::VideoTranslation(@$LastVideo['video_description'], @$LastVideo['video_translations'], 'description'), 140) ?>
                    </h2>
                    <div>
                        <a
                            href="<?= PATH ?>watch/87654<?= @$LastVideo['id'] . '/' . StaticFunctions::BrowseReferer() ?>">
                            <button type="button" class="billboard-button-play">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M3 22v-20l18 10-18 10z" />
                                </svg>
                                <span><?= StaticFunctions::lang('356_play') ?></span>
                            </button>
                        </a>
                        <a href="<?= PATH ?>browse/87654<?= @$LastVideo['id'] ?>">
                            <button type="button" class="billboard-button-info">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1 18h-2v-8h2v8zm-1-12.25c.69 0 1.25.56 1.25 1.25s-.56 1.25-1.25 1.25-1.25-.56-1.25-1.25.56-1.25 1.25-1.25z" />
                                </svg>
                                <span><?= StaticFunctions::lang('357_more') ?></span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <?php


            $VideoLevel = StaticFunctions::VideoAdulthoodLevel(@$LastVideo['video_level']);
            if ($VideoLevel != '__noLevel__') {
                echo '<div class="billboard-maturity">
                <span>' . $VideoLevel . '</span>
            </div>';
            }

            ?>

            <div data-single="row" data-send="<?= $SingleGenre['id'] ?>" data-hide="null"
                data-source="list/populer/movies/genre"
                data-title="<?= Staticfunctions::lang('553_popular-0', [
                                                                                                                                                    StaticFunctions::GenreTranslation($SingleGenre['genres_name'], $SingleGenre['genre_translations'])
                                                                                                                                                ]) ?>"
                data-speed="100" style="height:auto; position:absolute"
                class="main-carousel d-none d-lg-block list_videos list-container">
            </div>




        </div>

    </section>


    <section style="display: flow-root;width:100%;" class="entity-showcase">

        <div data-source="list/genres/movie" data-send="<?= $SingleGenre['id'] ?>" data-title="null"
            class="entity-carousel list_videos list-container list-container-relative">

            <div style="margin-top: -50px;">
                <div class="lolomoRow lolomoRow_title_card lolomoPreview" data-reactid="138">
                    <div class="rowHeader" data-reactid="139"><span class="rowTitle" data-reactid="140">&nbsp;</span>
                    </div>
                    <div class="rowContent" data-reactid="141">
                        <div class="slider" data-reactid="142">
                            <div class="smallTitleCard loadingTitle" data-reactid="143">
                                <div class="ratio-16x9 pulsate" style="-webkit-animation-delay:0s;-animation-delay:0s;"
                                    data-reactid="144"></div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="145">
                                <div class="ratio-16x9 pulsate"
                                    style="-webkit-animation-delay:0.1s;-animation-delay:0.1s;" data-reactid="146">
                                </div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="147">
                                <div class="ratio-16x9 pulsate"
                                    style="-webkit-animation-delay:0.2s;-animation-delay:0.2s;" data-reactid="148">
                                </div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="149">
                                <div class="ratio-16x9 pulsate"
                                    style="-webkit-animation-delay:0.3000000000000001s;-animation-delay:0.3000000000000001s;"
                                    data-reactid="150"></div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="151">
                                <div class="ratio-16x9 pulsate"
                                    style="-webkit-animation-delay:0.4s;-animation-delay:0.4s;" data-reactid="152">
                                </div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="153">
                                <div class="ratio-16x9 pulsate"
                                    style="-webkit-animation-delay:0.5s;-animation-delay:0.5s;" data-reactid="154">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <?php

    require_once StaticFunctions::View('V' . '/classic.footer.php');

    ?>