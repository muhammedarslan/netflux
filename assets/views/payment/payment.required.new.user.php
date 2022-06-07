    <?php

    $PageCss = [];
    $PageJs = [
        '/assets/netflux/js/payment.required.new.user.js'
    ];
    $PageNewUser = true;

    $route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
    $route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
    $UrlExplode = explode('/', rtrim(urldecode(strtok($route_path, '?')), '/'));

    if ($UrlExplode[1] != 'subscription') {
        StaticFunctions::go('subscription/info');
        exit;
    }

    StaticFunctions::new_session();

    require_once StaticFunctions::View('V' . '/classic.header.php');

    ?>

    <section class="box border-bottom">

        <div class="signup-content d-flex justify-content-center align-items-center">
            <div class="d-flex flex-column signup-form text-center">
                <div style="display:flex;margin:0 auto;margin-bottom:30px;" class="row">
                    <img src="<?= PATH ?>assets/media/Lock.png" width="50px" alt="">
                </div>
                <div class="">
                    <span class="step"><?= StaticFunctions::lang('472_step') ?></span>
                </div>
                <h1 class="step-title"><?= StaticFunctions::lang('473_determine-your-payment') ?></h1>

                <div class="paymenstTitlesDiv">
                    <h2 style="font-size:16px !important;" class="step-subtitle-larger mt-4">
                        <?= StaticFunctions::lang('474_your-membership-starts-as-soon-as-you') ?></h2>
                    <h2 style="font-weight:700;font-size:16px !important;" class="step-subtitle-larger mt-4">
                        <?= StaticFunctions::lang('475_no') ?></h2>
                    <h2 style="font-weight:700;font-size:16px !important;" class="step-subtitle-larger">
                        <?= StaticFunctions::lang('476_cancel-online-at-any') ?></h2>
                </div>


                <div class="secure-server-badge mt-4"><svg id="secure-server-icon" viewBox="0 0 12 16"
                        class="secure-server-badge--icon">
                        <g fill="none">
                            <g fill="#FFB53F">
                                <path
                                    d="M8.4 5L8.4 6.3 10 6.3 10 5C10 2.8 8.2 1 6 1 3.8 1 2 2.8 2 5L2 6.3 3.6 6.3 3.6 5C3.6 3.7 4.7 2.6 6 2.6 7.3 2.6 8.4 3.7 8.4 5ZM11 7L11 15 1 15 1 7 11 7ZM6.5 11.3C7 11.1 7.3 10.6 7.3 10.1 7.3 9.3 6.7 8.7 6 8.7 5.3 8.7 4.7 9.3 4.7 10.1 4.7 10.6 5 11.1 5.5 11.3L5.2 13.4 6.9 13.4 6.5 11.3Z">
                                </path>
                            </g>
                        </g>
                    </svg>
                    <div class="secure-server-badge--text"><?= Staticfunctions::lang('477_secure') ?></div>
                </div>


                <div class="paymentSelectorRow">

                    <div>
                        <div id="P1" onclick="PayWith('paypal');" class="nfTabSelectionWrapper"
                            data-uia="payment-choice+creditOrDebitOption"><a
                                class="nfTabSelection nfTabSelection--active paymentPicker standardHeight"
                                href="javascript:;">
                                <div class="mopNameAndLogos">
                                    <div class="nfTabSelection--text card-type-text paymentActive"
                                        data-uia="mop-type-text"><span
                                            class="selectionLabel"><?= Staticfunctions::lang('478_continue-with') ?></span>
                                    </div>
                                    <div class="logosContainer"><span class="logos logos-inline"><img alt=""
                                                class="logoIcon" style="width: auto;"
                                                src="<?= PATH ?>assets/media/paypal.svg"></span>
                                    </div>
                                </div><span class="ui-svg-icon ui-svg-icon--chevron pull-right pickerArrow"></span>
                            </a></div>
                        <div id="P2" onclick="PayWith('stripe');" class="nfTabSelectionWrapper"
                            data-uia="payment-choice+giftOption"><a
                                class="nfTabSelection nfTabSelection--active paymentPicker standardHeight"
                                href="javascript:;">
                                <div class="mopNameAndLogos">
                                    <div class="nfTabSelection--text card-type-text paymentActive"
                                        data-uia="mop-type-text"><span
                                            class="selectionLabel">&nbsp;<?= Staticfunctions::lang('479_continue-with-the') ?></span>
                                    </div>
                                    <div class="logosContainer"><span class="logos logos-inline"><img alt=""
                                                class="logoIcon"
                                                style="width:auto;transform: skewX(-10deg);margin-left: 3px;"
                                                src="<?= PATH ?>assets/media/stripe_pay.png"></span>
                                    </div>
                                </div><span class="ui-svg-icon ui-svg-icon--chevron pull-right pickerArrow"></span>
                            </a></div>
                    </div>

                </div>

                <div id="paypal-button-container" style="display: none;" class="PaymentLoading mt-4">
                    <span id="PaymentLoadingText"><?= Staticfunctions::lang('480_loading') ?></span>
                </div>

                <div id="PaypalSuccessText" style="display: none;" class="mt-4">
                    <span><?= Staticfunctions::lang('481_your-subscription-is-being-processed') ?></span>
                </div>



            </div>


        </div>
    </section>


    <?php

    require_once StaticFunctions::View('V' . '/classic.footer.php');

    ?>