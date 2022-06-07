<?php

$PageCss = [];
$PageJs = [];


require_once StaticFunctions::View('V' . '/admin.header.php');

?>

<div class="content">
    <?php


    $LastActivities = [];
    $GetActivities = $db->query("SELECT * FROM log  order by id DESC LIMIT 100", PDO::FETCH_ASSOC);
    if ($GetActivities->rowCount()) {
        foreach ($GetActivities as $row) {
            $LastActivities[$row['log_time']] = [
                'type' => 'log',
                'data' => $row,
                'time' => StaticFunctions::timerFormat($row['log_time'], time()) . ' ' . StaticFunctions::lang('8_before')
            ];
        }
    }

    $GetPayments = $db->query("SELECT * FROM payments WHERE payment_amount > 0 order by id DESC LIMIT 100", PDO::FETCH_ASSOC);
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



    <div class="dashboard-charts dashboard-charts--two">
        <div style="max-width: 100%;flex:0 0 100%;">
            <div class="activities">
                <div class="label">
                    <span><?= StaticFunctions::lang('140_recent') ?></span>
                </div>
                <ul>
                    <?php

                    foreach ($LastActivities as $key => $Activity) {
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
                    }

                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
