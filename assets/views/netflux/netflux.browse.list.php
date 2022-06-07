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


    ?>

    <style>
        .list-carousel {
            z-index: 5;
        }
        .list-carousel:hover {
            z-index: 7;
        }
    </style>   

    <section class="entity-showcase">

        <div style="padding-top: 80px; padding-bottom: 0;" class="entity-carousel">
            <div style="font-weight:normal;" class="carousel-title">
                <?= StaticFunctions::lang('355_my') ?>
            </div>
        </div>



        <div data-source="list/my/list" data-title="null" class="entity-carousel list_videos list-container list-container-relative">

            <div style="margin-top: -50px;">
                <div class="lolomoRow lolomoRow_title_card lolomoPreview" data-reactid="138">
                    <div class="rowHeader" data-reactid="139"><span class="rowTitle" data-reactid="140">&nbsp;</span>
                    </div>
                    <div class="rowContent" data-reactid="141">
                        <div class="slider" data-reactid="142">
                            <div class="smallTitleCard loadingTitle" data-reactid="143">
                                <div class="ratio-16x9 pulsate" style="-webkit-animation-delay:0s;-animation-delay:0s;" data-reactid="144"></div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="145">
                                <div class="ratio-16x9 pulsate" style="-webkit-animation-delay:0.1s;-animation-delay:0.1s;" data-reactid="146">
                                </div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="147">
                                <div class="ratio-16x9 pulsate" style="-webkit-animation-delay:0.2s;-animation-delay:0.2s;" data-reactid="148">
                                </div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="149">
                                <div class="ratio-16x9 pulsate" style="-webkit-animation-delay:0.3000000000000001s;-animation-delay:0.3000000000000001s;" data-reactid="150"></div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="151">
                                <div class="ratio-16x9 pulsate" style="-webkit-animation-delay:0.4s;-animation-delay:0.4s;" data-reactid="152">
                                </div>
                            </div>
                            <div class="smallTitleCard loadingTitle" data-reactid="153">
                                <div class="ratio-16x9 pulsate" style="-webkit-animation-delay:0.5s;-animation-delay:0.5s;" data-reactid="154">
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
