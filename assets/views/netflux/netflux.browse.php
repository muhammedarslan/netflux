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

    require_once StaticFunctions::View('V' . '/classic.header.php');

    $ProfileID = StaticFunctions::GetProfileId();
    $ProfileLevel = $db->query("SELECT profile_level from profiles WHERE id='{$ProfileID}' ")->fetch(PDO::FETCH_ASSOC)['profile_level'];

    $LastVideo = $db->query("SELECT * FROM series_and_movies WHERE '{$ProfileLevel}' <= video_level and video_type='movie' order by Rand() DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
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
    </style>
    <section class="border-0 top_first_section">


        <div class="position-relative d-flex align-items-center main-billboard" style="flex-wrap: wrap;">
            <img id="TopBrowseImg" style="width: 100%;max-height:820px;"
                src="<?= json_decode(@$LastVideo['video_images'], true)[1] ?>">
            <?php
            /*
            <video id="TopBrowseVideo" style="display: none;" loop muted width="100%" class="vm_vd" controls>
                <source src="<?= @$LastVideo['video_short_source'] ?>" type="video/mp4">
            <img src="<?= json_decode(@$LastVideo['video_images'], true)[1] ?>">
            </video>
            */
            ?>
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

            <div data-single="row" data-hide="null" data-source="list/populer"
                data-title="<?= StaticFunctions::lang('358_popular') ?>" data-speed="100"
                style="height:auto; position: absolute"
                class="main-carousel d-none d-lg-block list_videos list-container">

            </div>

        </div>

    </section>

    <section class="entity-showcase">
        <?php

        if ($ProfileLevel == 5) {

            $KidsGenre = $db->query("SELECT * from genres WHERE genres_name='Kids' ")->fetch(PDO::FETCH_ASSOC);

            if ($KidsGenre) {
                echo '<div data-source="list/genres/browse" data-single="row" data-hide="null" data-send="' . $KidsGenre['id'] . '" data-title="' . StaticFunctions::GenreTranslation($KidsGenre['genres_name'], $KidsGenre['genre_translations']) . '" class="entity-carousel list_videos list-container list-container-relative">

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
        </div>';
            }

            $Genres = $db->query("SELECT * FROM genres WHERE genres_name != 'Kids' order by Rand() LIMIT 10 ", PDO::FETCH_ASSOC);
        } else {
            $Genres = $db->query("SELECT * FROM genres order by Rand() LIMIT 10 ", PDO::FETCH_ASSOC);
        }


        if ($Genres->rowCount()) {
            foreach ($Genres as $row) {
                echo '<div data-source="list/genres/browse" data-single="row" data-hide="null" data-send="' . $row['id'] . '" data-title="' . StaticFunctions::GenreTranslation($row['genres_name'], $row['genre_translations']) . '" class="entity-carousel list_videos list-container list-container-relative">

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
        </div>';
            }
        }

        ?>



    </section>

    <?php

    require_once StaticFunctions::View('V' . '/classic.footer.php');

    ?>