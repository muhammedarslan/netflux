<header data-current-header="classic" class="position-absolute w-100 pt-1">
    <div class="ml-3 mr-3 ml-md-5 mr-md-5 p-2 pt-4">
        <div class="d-flex align-items-center">
            <a href="/<?= LANG ?>">
                <img src="<?= PATH ?>assets/netflux/images/logo.png" alt="Netflux Logo" class="logo">
            </a>
            <?php
            if ($PageOptions['View'] == 'home' || $PageOptions['View'] == 'register') : ?>
            <div class="ml-auto">
                <a href="/<?= LANG ?>/login" class="btn-login"><?= StaticFunctions::lang('219_login') ?></a>
            </div>
            <?php endif;


            if ($PageOptions['View'] == 'required') : ?>
            <div class="ml-auto">
                <?php

                    if (!isset($PageNewUser)) {
                        echo '  <a onclick="window.location=\'' . PATH . 'account/packets\';" href="javascript:;" class="btn-login">' . StaticFunctions::lang('326_change') . '</a>';
                    }

                    ?>
                <a style="margin-right: -10px;
    margin-left: 10px;" href="javascript:;" onclick="window.location='/log-out';"
                    class="btn-login"><?= StaticFunctions::lang('327_sign') ?></a>
            </div>
            <?php endif;

            if ($PageOptions['View'] == 'change.plan') : ?>
            <div class="ml-auto">
                <a href="<?= PATH ?>browse" class="btn-login"><?= StaticFunctions::lang('328_return-to') ?></a>
                <a style="margin-right: -10px;
    margin-left: 10px;" href="javascript:;" onclick="window.location='/log-out';"
                    class="btn-login"><?= StaticFunctions::lang('327_sign') ?></a>
            </div>
            <?php endif; ?>

        </div>
    </div>
</header>