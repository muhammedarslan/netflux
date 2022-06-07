<?php

$PageCss = [];
$PageJs = [
        '/assets/netflux/js/home.js'
];

require_once StaticFunctions::View('V' . '/classic.header.php');

?>

<style>
       .form-control.is-invalid,
        .was-validated .form-control:invalid {
            border: none;
            border-bottom: 2.5px solid #ffa00a;
            background-image: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23ffa00a' viewBox='0 0 12 12'><circle cx='6' cy='6' r='4.5'/><path stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/><circle cx='6' cy='8.2' r='.6' fill='%23ffa00a' stroke='none'/></svg>");
        }

        .invalid-feedback {
            color: #ffa00a;
        }

        .form-control.is-invalid:focus,
        .was-validated .form-control:invalid:focus {
            border-color: #ffa00a;
        }

        .m-progress:before {
            top: 45%;
            width: 30px;
            height: 30px;
        }
 </style>
    <section class="box">
        <div class="box-background position-absolute">
            <img src="<?= PATH ?>assets/netflux/images/background.jpg" alt="Netflux Background">
        </div>

        <div class="content position-relative d-flex justify-content-center align-items-center">
            <div class="d-flex flex-column mt-5 pt-4 mt-md-0 pt-md-0">
                <div class="d-flex justify-content-center mt-md-5">
                    <h1 class="text-white text-center font-weight-bold m-0 col-sm-10 col-md-8 mt-md-5">
                        <?= StaticFunctions::lang('363_unlimited-movies-series-and-much') ?></h1>
                </div>
                <h2 class="text-white text-center m-3">
                    <?= StaticFunctions::lang('364_watch-wherever-you-want-cancel-at-any') ?></h2>

                <div class="d-flex justify-content-center pt-lg-3 pb-3 order-1 order-lg-0 email-form">
                    <div style="height: 75px;" id="Div1" class="input-group col-sm-10 col-md-8 col-lg-9 col-xl-10 flex-column flex-lg-row form-email form-field">
                        <input data-button-id="Button1" data-error-text="<?= StaticFunctions::lang('442_please-enter-a-valid-e-mail') ?>" autocomplete="off" novalidate name="email" type="email" id="email1" class="form-control email-input home-email-input">
                        <label id="LabelEmail" for="email1"><?= StaticFunctions::lang('365_e-mail') ?></label>
                        <div style="height: 70px;" class="home-button-container input-group-append d-flex justify-content-center mt-2 mt-sm-3 mt-lg-0">
                            <button id="Button1" data-click-event="SignUp('email1');" class="btn trial-button" type="submit"><span><?= StaticFunctions::lang('366_start') ?>
                                </span><svg class="icon-svg" viewBox="0 0 6 12" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M.61 1.312l.78-.624L5.64 6l-4.25 5.312-.78-.624L4.36 6z" fill="#FFF">
                                    </path>
                                </svg></button>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-white text-center mt-2">
                        <?= StaticFunctions::lang('367_ready-to-watch-enter-your-e-mail') ?>
                    </h3>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container pt-5 pb-5 pl-md-4 pr-md-4 pl-lg-3 pr-lg-3">
            <div class="row">
                <div class="col-lg-6 d-flex align-items-center z-3">
                    <div class="mt-0 mt-sm-3 mt-md-3 mt-lg-0">
                        <h1 class="title text-white font-weight-bold mb-3 mb-lg-4">
                            <?= StaticFunctions::lang('431_enjoy-it-on-your') ?></h1>
                        <h2 class="subtitle text-white">
                            <?= StaticFunctions::lang('432_watch-smart-tvs-playstation-xbox') ?>
                        </h2>
                    </div>
                </div>
                <div class="col-lg-6 mb-1 mb-sm-4 mb-md-4 mb-lg-0">
                    <div class="position-relative video-tv">
                        <img src="<?= PATH ?>assets/netflux/images/tv.png" alt="Netflux TV" class="img-fluid">
                        <video autoplay playsinline muted loop>
                            <source src="<?= PATH ?>assets/netflux/images/video-tv.m4v" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container pt-5 pb-5 pl-md-4 pr-md-4 pl-lg-3 pr-lg-3">
            <div class="row">
                <div class="col-lg-6 order-1 order-lg-0 mb-1 mb-sm-4 mb-md-4 mb-lg-0">
                    <div class="position-relative mobile">
                        <img src="<?= PATH ?>assets/netflux/images/mobile.jpg" alt="Netflux Mobile" class="img-fluid">
                        <div class="mobile-card d-flex align-items-center p-2">
                            <div class="card-image">
                                <img src="<?= PATH ?>assets/netflux/images/boxshot.png" alt="Netflux Mobile">
                            </div>
                            <div class="content">
                                <h5 class="text-white m-0">Stranger Things</h5>
                                <label><?= StaticFunctions::lang('368_downloading') ?></label>
                            </div>
                            <div class="gif">
                                <img src="<?= PATH ?>assets/netflux/images/download-icon.gif" alt="Netflux Mobile">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center z-3">
                    <div class="mt-0 mt-sm-3 mt-md-3 mt-lg-0">
                        <h1 class="title text-white font-weight-bold mb-3 mb-lg-4">
                            <?= StaticFunctions::lang('369_download-your-shows-to-watch-on-the') ?></h1>
                        <h2 class="subtitle text-white">
                            <?= StaticFunctions::lang('370_save-your-data-and-watch-all-your') ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container pt-5 pb-5 pl-md-4 pr-md-4 pl-lg-3 pr-lg-3">
            <div class="row">
                <div class="col-lg-6 d-flex align-items-center z-3">
                    <div class="mt-0 mt-sm-3 mt-md-3 mt-lg-0">
                        <h1 class="title text-white font-weight-bold mb-3 mb-lg-4">
                            <?= StaticFunctions::lang('371_watch') ?></h1>
                        <h2 class="subtitle text-white">
                            <?= StaticFunctions::lang('433_watch-unlimited-movies-and-tv-shows-on') ?>
                        </h2>
                    </div>
                </div>
                <div class="col-lg-6 mb-1 mb-sm-4 mb-md-4 mb-lg-0">
                    <div class="position-relative video-devices">
                        <img src="<?= PATH ?>assets/netflux/images/device-pile.png" alt="Netflux TV" class="img-fluid">
                        <video autoplay playsinline muted loop>
                            <source src="<?= PATH ?>assets/netflux/images/video-devices.m4v" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container pt-5 pb-5 faq">
            <div class="row justify-content-center">
                <div class="col-12 pl-4 pr-4 pl-lg-0 pr-lg-0 mt-0 mt-sm-3 mt-md-3 mt-xl-3 mb-lg-4">
                    <h1 class="title text-white text-center font-weight-bold">
                        <?= StaticFunctions::lang('372_frequently-asked') ?></h1>
                </div>
                <div class="col-md-10 col-lg-9 col-xl-9 col-xl-8 pl-0 pr-0 pl-sm-5 pr-sm-5 pl-md-0 pr-md-0">
                    <div class="accordion mt-3" id="accordion">
                        <div class="form-group mb-2">
                            <button type="button" data-toggle="collapse" data-target="#one">
                                <?= StaticFunctions::lang('373_what-is') ?>
                                <span>
                                    <svg version="1.1" fill="#FFF" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                        <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z" />
                                    </svg>
                                </span>
                            </button>
                            <div id="one" class="collapse" data-parent="#accordion">
                                <div class="card-body pl-4 pr-4">
                                    <?= StaticFunctions::lang('374_netflux-is-a-streaming-service-that') ?>
                                    <br>
                                    <br>
                                    <?= StaticFunctions::lang('375_you-can-watch-as-much-as-you-want') ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <button type="button" data-toggle="collapse" data-target="#two">
                                <?= StaticFunctions::lang('437_how-much-does-netflux') ?>
                                <span>
                                    <svg version="1.1" fill="#FFF" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                        <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z" />
                                    </svg>
                                </span>
                            </button>
                            <div id="two" class="collapse" data-parent="#accordion">
                                <div class="card-body pl-4 pr-4">
                                    <?= StaticFunctions::lang('434_watch-netflux-on-your-smartphone') ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <button type="button" data-toggle="collapse" data-target="#three">
                                <?= StaticFunctions::lang('376_where-can-i') ?>
                                <span>
                                    <svg version="1.1" fill="#FFF" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                        <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z" />
                                    </svg>
                                </span>
                            </button>
                            <div id="three" class="collapse" data-parent="#accordion">
                                <div class="card-body pl-4 pr-4">
                                    <?= StaticFunctions::lang('435_watch-anywhere-anytime-on-unlimited') ?>
                                    <br>
                                    <br>
                                    <?= StaticFunctions::lang('436_you-can-also-download-your-favorite') ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <button type="button" data-toggle="collapse" data-target="#four">
                                <?= StaticFunctions::lang('377_how-do-i') ?>
                                <span>
                                    <svg version="1.1" fill="#FFF" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                        <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z" />
                                    </svg>
                                </span>
                            </button>
                            <div id="four" class="collapse" data-parent="#accordion">
                                <div class="card-body pl-4 pr-4">
                                    <?= StaticFunctions::lang('378_netflux-is-flexible-no-annoying') ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <button type="button" data-toggle="collapse" data-target="#five">
                                <?= StaticFunctions::lang('438_what-can-i-watch-on') ?>
                                <span>
                                    <svg version="1.1" fill="#FFF" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                        <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z" />
                                    </svg>
                                </span>
                            </button>
                            <div id="five" class="collapse" data-parent="#accordion">
                                <div class="card-body pl-4 pr-4">
                                    <?= StaticFunctions::lang('379_netflux-has-an-extensive-library-of') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column mt-4 mt-md-0 mt-lg-5 mb-sm-3 mb-md-4">
                        <div class="d-flex justify-content-center pt-lg-3 order-1 order-lg-0 email-form">
                            <div style="height: 75px;" id="Div2" class="input-group col-md-10 col-lg-12 col-xl-11 flex-column flex-lg-row form-email form-field">
                                <input type="email" data-button-id="Button2" data-error-text="<?= StaticFunctions::lang('442_please-enter-a-valid-e-mail') ?>" autocomplete="off" novalidate id="email2" class="form-control email-input home-email-input">
                                <label for="email2" id="LabelEmail2"><?= StaticFunctions::lang('365_e-mail') ?></label>
                                <div style="height: 70px;" class="home-button-container input-group-append d-flex justify-content-center mt-2 mt-sm-3 mt-lg-0">
                                    <button id="Button2" data-click-event="SignUp('email2');" class="btn trial-button" type="button"><span><?= StaticFunctions::lang('366_start') ?> </span><svg class="icon-svg" viewBox="0 0 6 12" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M.61 1.312l.78-.624L5.64 6l-4.25 5.312-.78-.624L4.36 6z" fill="#FFF"></path>
                                        </svg></button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-white text-center subtitle mt-md-4 mt-lg-3 mt-2">
                                <?= StaticFunctions::lang('380_ready-to-watch-enter-your-email-to') ?>
                            </h3>
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
