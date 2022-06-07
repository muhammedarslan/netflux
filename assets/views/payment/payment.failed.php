    <?php

    $PageCss = [];
    $PageJs = [];

    $PageOptions['View'] = 'payment_failed';
    $__PageTitle = StaticFunctions::lang('392_payment');

    require_once StaticFunctions::View('V' . '/classic.header.php');

    ?>
    <section class="box border-0">
        <div style="height:-webkit-fill-available;" class="box-background position-absolute login_background">
            <img src="<?= PATH ?>assets/netflux/images/background.jpg" alt="Netflux Background">
        </div>

        <div class="login-content d-flex justify-content-center align-items-center">
            <div style="height: 370px;" class="form_background">
                <div class="d-flex flex-column login login_d">
                    <div class="d-flex flex-column justify-content-center">
                        <center>
                            <img width="100px" src="<?= PATH ?>assets/media/payment_failed.png" />
                        </center>
                        <h1 class="login-title mb-4 text-center mt-4">
                            <?= StaticFunctions::lang('393_your-payment-transaction') ?>
                        </h1>
                    </div>

                    <div class="mt-2">
                        <a href="/" class="btn-plans rounded-4"><?= StaticFunctions::lang('328_return-to') ?></a>
                    </div>



                </div>
            </div>
        </div>
    </section>
    <?php
    require_once StaticFunctions::View('V' . '/classic.footer2.php');

    ?>