<div class="backdrop js-backdrop"></div>
<div class="page">
    <div class="page-header">
        <div class="logo">
            <a href="<?= PATH ?>admin/dashboard"><img width="130px" src="<?= PATH ?>assets/netflux/images/logo.png" alt="" /></a>
        </div>
        <div class="topbar">
            <div>
                <a target="_blank" href="<?= PATH ?>browse" class="visit-button">
                    <?= StaticFunctions::lang('220_discover') ?>
                    <img src="/assets/console/imgs/link.svg" alt="">
                </a>
            </div>
            <div class="navigation">
                <div class="mobile-toggle js-mobile-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <?php
                $Languages = AppLanguage::GetAllowedLangs();
                $SelectedLang = LANG;
                ?>
                <div onclick="return false;" class="user-menu">
                    <div class="toggle">
                        <?= $Languages[LANG]['LangName'] ?>
                        <em class="fa fa-chevron-down"></em>
                    </div>
                    <ul style="z-index: 1;">
                        <?php
                        unset($Languages[LANG]);
                        foreach ($Languages as $key => $value) {
                            echo '<li>
                            <a class="no-barba" href="?hl=' . $key . '">
                                ' . $value['LangName'] . '
                            </a>
                        </li>';
                        }
                        ?>
                    </ul>
                </div>
                <div onclick="MenuClick(this); return false;" class="user-menu">
                    <div class="toggle">
                        <?= $_SESSION['UserSession']->real_name ?>
                        <em class="fa fa-chevron-down"></em>
                    </div>
                    <ul style="z-index: 1;">
                        <li>
                            <a href="javascript:;" class="js-modal-toggle" data-target="first-modal2_profile" onclick="Profile(<?= StaticFunctions::get_id() ?>);">
                                <?= StaticFunctions::lang('221_profile') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= PATH ?>admin/activities">
                                <?= StaticFunctions::lang('222_records') ?>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="window.location='/log-out';">
                                <?= StaticFunctions::lang('223_sign') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="sidebar js-sidebar">
            <ul>
                <li>
                    <a href="<?= PATH ?>admin/dashboard">
                        <img src="<?= PATH ?>assets/console/imgs/dashboard-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('164_control') ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/movies">
                        <img src="<?= PATH ?>assets/console/imgs/film-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('108_movies') ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/series">
                        <img src="<?= PATH ?>assets/console/imgs/tv-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('109_series') ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/genres">
                        <img src="<?= PATH ?>assets/console/imgs/ghost-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('103_species') ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/actors">
                        <img src="<?= PATH ?>assets/console/imgs/open-arm-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('116_players') ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/directors">
                        <img src="<?= PATH ?>assets/console/imgs/clapperboard-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('101_directors') ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/users">
                        <img src="<?= PATH ?>assets/console/imgs/user-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('102_users') ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/plans">
                        <img src="<?= PATH ?>assets/console/imgs/equalizer-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('104_plans') ?></span>
                    </a>
                </li>
                <li class="js-accordion">
                    <a href="javascript:;" onclick="LoadAdminPage();">
                        <img src="<?= PATH ?>assets/console/imgs/settings-line.svg" alt="" />
                        <span><?= StaticFunctions::lang('225_settings') ?></span>
                        <img src="<?= PATH ?>assets/console/imgs/arrow-down-s-line.svg" alt="" />
                    </a>
                    <ul class="js-accordion-item">
                        <li>
                            <a href="<?= PATH ?>admin/billing">
                                <?= StaticFunctions::lang('105_paypal-stripe-amp') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= PATH ?>admin/currencies">
                                <?= StaticFunctions::lang('461_currencies') ?>
                            </a>
                        </li>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/avatars">
                        <?= StaticFunctions::lang('520_profile') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/payments">
                        <?= StaticFunctions::lang('226_payment') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/languages">
                        <?= StaticFunctions::lang('106_language') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= PATH ?>admin/activities">
                        <?= StaticFunctions::lang('140_recent') ?>
                    </a>
                </li>
            </ul>
            </li>
            </ul>
        </div>