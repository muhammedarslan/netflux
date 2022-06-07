    <?php

    $PageCss = [];
    $PageJs = [];

    require_once StaticFunctions::View('V' . '/classic.header.php');
    $URL = PROTOCOL . DOMAIN . PATH . LANG . '/login';
    echo "<script type='text/javascript'>setTimeout(()=>{document.location.href='{$URL}';},1000);</script>";
    echo '<META HTTP-EQUIV="refresh" content="1;URL=' . $URL . '">';

    ?>
    <section class="box border-0">
        <div style="height:-webkit-fill-available;" class="box-background position-absolute login_background">
            <img src="<?= PATH ?>assets/netflux/images/background.jpg" alt="Netflux Background">
        </div>

        <div class="login-content d-flex justify-content-center align-items-center">
            <div style="height: 230px;" class="form_background">
                <div class="d-flex flex-column login login_d">
                    <div class="d-flex flex-column justify-content-center">
                        <h1 class="login-title mb-4 text-center">
                            <?= StaticFunctions::lang('381_logging-out') ?>
                        </h1>
                    </div>

                    <div class="mt-4">
                        <a href="javascript:;" id="Button2"
                            class="btn-plans rounded-4 m-progress"><?= StaticFunctions::lang('223_sign') ?></a>
                    </div>



                </div>
            </div>
        </div>
    </section>
    <?php
    StaticFunctions::LogOut($db);
    require_once StaticFunctions::View('V' . '/classic.footer2.php');

    ?>