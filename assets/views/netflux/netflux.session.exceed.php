    <?php

    StaticFunctions::NoBarba();
    $PageCss = [];
    $PageJs = [
        '/assets/netflux/js/session.exceed.js'
    ];

    require_once StaticFunctions::View('V' . '/classic.header.php');

    ?>
    <section class="box border-0">
        <div class="box-background position-absolute login_background">
            <img src="<?= PATH ?>assets/netflux/images/background.jpg" alt="Netflux Background">
        </div>

        <div class="login-content d-flex justify-content-center align-items-center">
            <div style="height: 420px;" class="form_background">
                <div class="d-flex flex-column login login_d">
                    <div class="d-flex flex-column justify-content-center">
                        <h1 class="login-title mb-4 text-center"><?= StaticFunctions::lang('388_number-of-devices') ?>
                        </h1>
                    </div>


                    <div class="mt-2 login-signup text-center">
                        <?= StaticFunctions::lang('573_session-exceed1') ?>
                    </div>
                    <div class="mt-2 login-signup text-center">
                        <?= StaticFunctions::lang('574_session-exceed2') ?>
                    </div>

                    <div class="mt-4">
                        <a href="javascript:;" id="Button1" class="btn-plans rounded-4"><?= StaticFunctions::lang('389_end-other') ?></a>
                    </div>

                    <div class="mt-4">
                        <a href="javascript:;" onclick="window.location='/log-out';" id="Button2" class="btn-plans rounded-4"><?= StaticFunctions::lang('223_sign') ?></a>
                    </div>



                </div>
            </div>
        </div>
    </section>
    <?php

    require_once StaticFunctions::View('V' . '/classic.footer.php');

    ?>