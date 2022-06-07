<?php

$PageCss = [];
$PageJs = [
    '/assets/console/js/apexcharts.js',
    '/assets/console/js/dashboard.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');

?>

<div class="content">
    <?php

    if (!is_writable(APP_DIR . '/languages')) {
        echo '<div class="alert alert-danger" role="alert">
        <strong style="font-weight: 700;">' . StaticFunctions::lang('159_important') . '</strong>
        ' . StaticFunctions::lang('160_allow-write-permissions-to-the-app') . '
    </div>';
    }

    if (!is_writable(ROOT_DIR . '/assets/stream')) {
        echo '<div class="alert alert-danger" role="alert">
        <strong style="font-weight: 700;">' . StaticFunctions::lang('159_important') . '</strong>
        ' . StaticFunctions::lang('161_allow-write-permissions-to-the-assets') . '
    </div>';
    }

    if (!is_writable(ROOT_DIR . '/assets/media/netflux')) {
        echo '<div class="alert alert-danger" role="alert">
        <strong style="font-weight: 700;">' . StaticFunctions::lang('159_important') . '</strong>
        ' . StaticFunctions::lang('162_allow-write-permissions-to-the-assets') . '
    </div>';
    }

    if (!is_writable(ROOT_DIR . '/assets/media/avatars')) {
        echo '<div class="alert alert-danger" role="alert">
        <strong style="font-weight: 700;">' . StaticFunctions::lang('159_important') . '</strong>
        ' . StaticFunctions::lang('163_allow-write-permissions-to-the-assets') . '
    </div>';
    }


    $Count1Q = $db->query("SELECT * FROM series_and_movies WHERE video_type='movie' ", PDO::FETCH_ASSOC);
    $Count1 = $Count1Q->rowCount();

    $Count2Q = $db->query("SELECT * FROM series_and_movies WHERE video_type='series' ", PDO::FETCH_ASSOC);
    $Count2 = $Count2Q->rowCount();

    $Count3Q = $db->query("SELECT * FROM series_and_movies WHERE video_type='episode' ", PDO::FETCH_ASSOC);
    $Count3 = $Count3Q->rowCount();

    $Count4Q = $db->query("SELECT * FROM users WHERE status=1 ", PDO::FETCH_ASSOC);
    $Count4 = $Count4Q->rowCount();

    $Now = time();
    $Count5Q = $db->query("SELECT * FROM payments WHERE payment_amount > 0 and payment_finish_time > $Now ", PDO::FETCH_ASSOC);
    $Count5 = $Count5Q->rowCount();

    $Count6Q = $db->query("SELECT * FROM packets ", PDO::FETCH_ASSOC);
    $Count6 = $Count6Q->rowCount();

    $DailyEarn = 0;
    $WeeklyEarn = 0;
    $MontlyEarn = 0;

    $Before1Month = time() - (60 * 60 * 24 * 30);
    $Before1Week  = time() - (60 * 60 * 24 *  7);
    $Before1Day   = time() - (60 * 60 * 24 *  1);

    $GetLastMonthPayments = $db->query("SELECT * FROM payments WHERE payment_amount > 0 and payment_time > $Before1Month  ", PDO::FETCH_ASSOC);
    if ($GetLastMonthPayments->rowCount()) {
        foreach ($GetLastMonthPayments as $row) {
            $MontlyEarn += $row['payment_usd'];

            if ($row['payment_time'] > $Before1Week) {
                $WeeklyEarn += $row['payment_usd'];
            }

            if ($row['payment_time'] > $Before1Day) {
                $DailyEarn += $row['payment_usd'];
            }
        }
    }

    $LastActivities = [];
    $GetActivities = $db->query("SELECT * FROM log  order by id DESC LIMIT 4", PDO::FETCH_ASSOC);
    if ($GetActivities->rowCount()) {
        foreach ($GetActivities as $row) {
            $LastActivities[$row['log_time']] = [
                'type' => 'log',
                'data' => $row,
                'time' => StaticFunctions::timerFormat($row['log_time'], time()) . ' ' . StaticFunctions::lang('8_before')
            ];
        }
    }

    $GetPayments = $db->query("SELECT * FROM payments WHERE payment_amount > 0 order by id DESC LIMIT 4", PDO::FETCH_ASSOC);
    if ($GetPayments->rowCount()) {
        foreach ($GetPayments as $row) {
            $LastActivities[$row['payment_time']] = [
                'type' => 'payment',
                'data' => $row,
                'time' => StaticFunctions::timerFormat($row['payment_time'], time()) . ' ' . StaticFunctions::lang('8_before')
            ];
        }
    }

    krsort($LastActivities);

    ?>

    <div class="content-hero">
        <div class="title">
            <div class="hero">
                <?= StaticFunctions::lang('164_control') ?>
            </div>
            <p><?= StaticFunctions::lang('423_hi-0-it-s-great-to-see-you-here', [
                    $_SESSION['UserSession']->real_name
                ]) ?></p>
        </div>
    </div>

    <div class="dashboard-charts">
        <div>
            <img src="<?= PATH ?>assets/console/imgs/film-line.svg" alt="" />
            <div class="count">
                <?= $Count1 ?>
                <p><?= StaticFunctions::lang('165_total') ?></p>
            </div>
            <a href="<?= PATH ?>admin/movies" class="link"><?= StaticFunctions::lang('166_show-all') ?></a>
        </div>
        <div>
            <img src="<?= PATH ?>assets/console/imgs/tv-line.svg" alt="" />
            <div class="count">
                <?= $Count2 ?>
                <p><?= StaticFunctions::lang('167_total') ?></p>
            </div>
            <a href="<?= PATH ?>admin/series" class="link"><?= StaticFunctions::lang('168_show-all') ?></a>
        </div>
        <div>
            <img src="<?= PATH ?>assets/console/imgs/stack-line.svg" alt="" />
            <div class="count">
                <?= $Count3 ?>
                <p><?= StaticFunctions::lang('169_total') ?></p>
            </div>
            <a href="<?= PATH ?>admin/series" class="link"><?= StaticFunctions::lang('170_show-all') ?></a>
        </div>
        <div>
            <img src="<?= PATH ?>assets/console/imgs/user-line.svg" alt="" />
            <div class="count">
                <?= $Count4 ?>
                <p><?= StaticFunctions::lang('171_total-active') ?></p>
            </div>
            <a href="<?= PATH ?>admin/users" class="link"><?= StaticFunctions::lang('172_show-all') ?></a>
        </div>
        <div>
            <img src="<?= PATH ?>assets/console/imgs/user-heart-line.svg" alt="" />
            <div class="count">
                <?= $Count5 ?>
                <p><?= StaticFunctions::lang('173_total-active') ?></p>
            </div>
            <a href="<?= PATH ?>admin/payments" class="link"><?= StaticFunctions::lang('174_show-all') ?></a>
        </div>
        <div>
            <img src="<?= PATH ?>assets/console/imgs/equalizer-line.svg" alt="" />
            <div class="count">
                <?= $Count6 ?>
                <p><?= StaticFunctions::lang('175_total-active') ?></p>
            </div>
            <a href="<?= PATH ?>admin/plans" class="link"><?= StaticFunctions::lang('176_show') ?></a>
        </div>
    </div>

    <div class="dashboard-charts dashboard-charts--two">
        <div class="history-column js-tabs">
            <div class="head">
                <div class="label"><?= StaticFunctions::lang('177_total') ?></div>
                <ul class="js-tab-nav">
                    <li>
                        <a href="javascript:;" class="active" data-target="daily"><?= StaticFunctions::lang('178_diary') ?></a>
                    </li>
                    <li>
                        <a href="javascript:;" data-target="weekly"><?= StaticFunctions::lang('179_weekly') ?></a>
                    </li>
                    <li>
                        <a href="javascript:;" data-target="monthly"><?= StaticFunctions::lang('180_monthly') ?></a>
                    </li>
                </ul>
            </div>
            <div class="js-tab-content active" data-name="daily">
                <div class="big-text">$<?= number_format($DailyEarn, 2) ?></div>
                <img src="<?= PATH ?>assets/console/imgs/cash-outline.svg" alt="" />
                <p class="text-right">
                    <a href="<?= PATH ?>admin/payments" class="link"><?= StaticFunctions::lang('181_view-subscription') ?></a>
                </p>
            </div>
            <div class="js-tab-content" data-name="weekly">
                <div class="big-text">$<?= number_format($WeeklyEarn, 2) ?></div>
                <img src="<?= PATH ?>assets/console/imgs/cash-outline.svg" alt="" />
                <p class="text-right">
                    <a href="<?= PATH ?>admin/payments" class="link"><?= StaticFunctions::lang('181_view-subscription') ?></a>
                </p>
            </div>
            <div class="js-tab-content" data-name="monthly">
                <div class="big-text">$<?= number_format($MontlyEarn, 2) ?></div>
                <img src="<?= PATH ?>assets/console/imgs/cash-outline.svg" alt="" />
                <p class="text-right">
                    <a href="<?= PATH ?>admin/payments" class="link"><?= StaticFunctions::lang('181_view-subscription') ?></a>
                </p>
            </div>
        </div>
        <div>
            <div class="activities">
                <div class="label">
                    <span><?= StaticFunctions::lang('140_recent') ?></span>
                    <a href="<?= PATH ?>admin/activities" class="link"><?= StaticFunctions::lang('182_see') ?></a>
                </div>
                <ul>
                    <?php

                    $N = 0;
                    foreach ($LastActivities as $key => $Activity) {
                        if ($N > 3) break;
                        $Text = '';
                        if ($Activity['type'] == 'log') {
                            $Class = 'register';
                            $Data = json_decode($Activity['data']['log_data'], true);

                            if (isset($Data['Login'])) {
                                if ($Data['Login']['Type'] == 'Register') {
                                    $Text = StaticFunctions::lang('141_registered');
                                } else {
                                    $Text = StaticFunctions::lang('142_logged');
                                }
                            }
                        } else {
                            $Class = 'subscribe';
                            $Text = StaticFunctions::lang('143_subscribed');
                        }

                        $Email = '';
                        $UserID = $Activity['data']['user_id'];
                        $UserEmail = $db->query("SELECT email FROM users WHERE id = '{$UserID}'")->fetch(PDO::FETCH_ASSOC);
                        if ($UserEmail) {
                            $Email = $UserEmail['email'];
                        }

                        echo '<li class="' . $Class . '">
                        <span>
                            <p>' . $Email . '</p>
                            ' . $Text . '
                        </span>
                        <span>' . $Activity['time'] . '</span>
                    </li>';

                        $N++;
                    }

                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div style="width: 100%;max-width:100%;" class="dashboard-charts">
        <div style="width: 100%;max-width:100%;flex:0 0 100%" id="chart">

        </div>
    </div>

</div>


<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
