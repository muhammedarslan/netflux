<?php

StaticFunctions::NoBarba();

$PageCss = [
    '/assets/netflux/css/tingle2.css'
];
$PageJs = [
    'https://js.stripe.com/v3/',
    '/assets/netflux/js/tingle.js',
    '/assets/netflux/js/topbar.min.js',
    '/assets/netflux/js/plan.js'
];

$Plans = [];

$Packets = $db->query("SELECT * FROM packets order by id ASC", PDO::FETCH_ASSOC);
if ($Packets->rowCount()) {
    foreach ($Packets as $row) {
        $Plans[StaticFunctions::PacketTranslation($row['packet_name'], $row['packet_translations'])]  = [
            'PacketID' => $row['id'],
            'PlanID' => StaticFunctions::random(12),
            'MaxSession' => $row['max_session_count'],
            'MaxProfile' => $row['max_profile_count'],
            'Price'      => StaticFunctions::FloatPrice($row['packet_price'] * UserCurrency['exchange_rate'], UserCurrency['rounding_type']),
            'Properties' => json_decode($row['packet_properties'], true)
        ];
    }
}

$Me = StaticFunctions::get_id();
$MyPacket = $db->query("SELECT * FROM users WHERE    id = '{$Me}'")->fetch(PDO::FETCH_ASSOC);
$MyPacketID = $MyPacket['user_packet'];


require_once StaticFunctions::View('V' . '/classic.header.php');

?>
<section class="box border-bottom">

    <div class="h-100 content">
        <div class="plan-container">
            <div class="pl-3 pr-3">
                <h1 class="step-title"><?= StaticFunctions::lang('308_choose-the-plan-that-suits-you') ?></h1>
                <h2 class="step-subtitle">
                    <?= StaticFunctions::lang('309_upgrade-or-downgrade-your-plan-at-any') ?></h2>
            </div>
            <div class="d-flex flex-column align-items-end mt-3 sticky-buttons">
                <div class="packages d-flex justify-content-around">
                    <?php
                    $n = 0;
                    foreach ($Plans as $key => $Plan) {
                        $s = '';
                        if ($MyPacketID == $Plan['PacketID']) $s = 'checked';
                        echo '<label style="cursor:pointer;" class="plan-select">
                        <input type="radio" name="select_plan" id="' . $Plan['PlanID'] . '" value="' . $Plan['PacketID'] . '" ' . $s . '>
                        <span class="d-flex justify-content-center align-items-center checkmark">' . StaticFunctions::lang($key) . '</span>
                    </label>';
                        $n++;
                    }

                    ?>
                </div>
            </div>

            <table class="d-flex flex-column">
                <tbody class="d-flex flex-column">
                    <tr class="pricing-row d-flex align-items-center flex-wrap flex-sm-nowrap">
                        <td class="pricing-feature"><?= StaticFunctions::lang('310_monthly') ?></td>
                        <?php
                        $n = 0;
                        foreach ($Plans as $key => $Plan) {
                            $s = '';
                            if ($MyPacketID == $Plan['PacketID']) $s = 'selected';
                            echo '<td class="pricing-feature-cell ' . $s . '" data-sid="' . $Plan['PlanID'] . '" >' . StaticFunctions::ShowPrice($Plan['Price'], UserCurrency['currency_symbol'], UserCurrency['symbol_float']) . '</td>';
                            $n++;
                        }

                        ?>

                    </tr>
                    <tr class="pricing-row d-flex align-items-center flex-wrap flex-sm-nowrap">
                        <td class="pricing-feature"><?= StaticFunctions::lang('270_hd') ?></td>
                        <?php
                        $n = 0;
                        foreach ($Plans as $key => $Plan) {
                            $s = '';
                            if ($MyPacketID == $Plan['PacketID']) $s = 'selected';
                            if ($Plan['Properties']['HD']) {
                                echo '<td class="pricing-feature-cell ' . $s . '" data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <g>
                                    <path d="M17.6,56.4c-0.6,0-1.3-0.2-1.8-0.7L0.7,40.6c-1-1-1-2.6,0-3.5c1-1,2.6-1,3.5,0l13.4,13.4L59.7,8.3c1-1,2.6-1,3.5,0
		c1,1,1,2.6,0,3.5L19.4,55.7C18.9,56.2,18.3,56.4,17.6,56.4z" />
                                </g>
                            </svg>
                        </td>';
                            } else {
                                echo ' <td class="pricing-feature-cell ' . $s . '"  data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z"></path>
                            </svg>
                        </td>';
                            }
                            $n++;
                        }

                        ?>
                    </tr>
                    <tr class="pricing-row d-flex align-items-center flex-wrap flex-sm-nowrap">
                        <td class="pricing-feature"><?= StaticFunctions::lang('311_ultra-hd') ?></td>
                        <?php
                        $n = 0;
                        foreach ($Plans as $key => $Plan) {
                            $s = '';
                            if ($MyPacketID == $Plan['PacketID']) $s = 'selected';
                            if ($Plan['Properties']['UltraHD']) {
                                echo '<td class="pricing-feature-cell ' . $s . '" data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <g>
                                    <path d="M17.6,56.4c-0.6,0-1.3-0.2-1.8-0.7L0.7,40.6c-1-1-1-2.6,0-3.5c1-1,2.6-1,3.5,0l13.4,13.4L59.7,8.3c1-1,2.6-1,3.5,0
		c1,1,1,2.6,0,3.5L19.4,55.7C18.9,56.2,18.3,56.4,17.6,56.4z" />
                                </g>
                            </svg>
                        </td>';
                            } else {
                                echo ' <td class="pricing-feature-cell ' . $s . '"  data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z"></path>
                            </svg>
                        </td>';
                            }
                            $n++;
                        }

                        ?>
                    </tr>
                    <tr class="pricing-row d-flex align-items-center flex-wrap flex-sm-nowrap">
                        <td class="pricing-feature"><?= StaticFunctions::lang('312_screens-you-can-watch-at-the-same') ?>
                        </td>
                        <?php
                        $n = 0;
                        foreach ($Plans as $key => $Plan) {
                            $s = '';
                            if ($MyPacketID == $Plan['PacketID']) $s = 'selected';
                            echo '<td class="pricing-feature-cell ' . $s . '" data-sid="' . $Plan['PlanID'] . '" >' . $Plan['MaxSession'] . '</td>';
                            $n++;
                        }

                        ?>
                    </tr>
                    <tr class="pricing-row d-flex align-items-center flex-wrap flex-sm-nowrap">
                        <td class="pricing-feature">
                            <?= StaticFunctions::lang('313_watch-on-your-laptop-tv-phone-and') ?>
                        </td>
                        <?php
                        $n = 0;
                        foreach ($Plans as $key => $Plan) {
                            $s = '';
                            if ($MyPacketID == $Plan['PacketID']) $s = 'selected';
                            if ($Plan['Properties']['AllDevices']) {
                                echo '<td class="pricing-feature-cell ' . $s . '" data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <g>
                                    <path d="M17.6,56.4c-0.6,0-1.3-0.2-1.8-0.7L0.7,40.6c-1-1-1-2.6,0-3.5c1-1,2.6-1,3.5,0l13.4,13.4L59.7,8.3c1-1,2.6-1,3.5,0
		c1,1,1,2.6,0,3.5L19.4,55.7C18.9,56.2,18.3,56.4,17.6,56.4z" />
                                </g>
                            </svg>
                        </td>';
                            } else {
                                echo ' <td class="pricing-feature-cell ' . $s . '"  data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z"></path>
                            </svg>
                        </td>';
                            }
                            $n++;
                        }

                        ?>
                    </tr>
                    <tr class="pricing-row d-flex align-items-center flex-wrap flex-sm-nowrap">
                        <td class="pricing-feature"><?= StaticFunctions::lang('314_unlimited-movies-and-tv') ?></td>
                        <?php
                        $n = 0;
                        foreach ($Plans as $key => $Plan) {
                            $s = '';
                            if ($MyPacketID == $Plan['PacketID']) $s = 'selected';
                            if ($Plan['Properties']['UnlimitedMovies']) {
                                echo '<td class="pricing-feature-cell ' . $s . '" data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <g>
                                    <path d="M17.6,56.4c-0.6,0-1.3-0.2-1.8-0.7L0.7,40.6c-1-1-1-2.6,0-3.5c1-1,2.6-1,3.5,0l13.4,13.4L59.7,8.3c1-1,2.6-1,3.5,0
		c1,1,1,2.6,0,3.5L19.4,55.7C18.9,56.2,18.3,56.4,17.6,56.4z" />
                                </g>
                            </svg>
                        </td>';
                            } else {
                                echo ' <td class="pricing-feature-cell ' . $s . '"  data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z"></path>
                            </svg>
                        </td>';
                            }
                            $n++;
                        }

                        ?>
                    </tr>
                    <tr class="pricing-row d-flex align-items-center flex-wrap flex-sm-nowrap">
                        <td class="pricing-feature"><?= StaticFunctions::lang('315_cancel') ?></td>
                        <?php
                        $n = 0;
                        foreach ($Plans as $key => $Plan) {
                            $s = '';
                            if ($MyPacketID == $Plan['PacketID']) $s = 'selected';
                            if ($Plan['Properties']['CancelAnytime']) {
                                echo '<td class="pricing-feature-cell ' . $s . '" data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <g>
                                    <path d="M17.6,56.4c-0.6,0-1.3-0.2-1.8-0.7L0.7,40.6c-1-1-1-2.6,0-3.5c1-1,2.6-1,3.5,0l13.4,13.4L59.7,8.3c1-1,2.6-1,3.5,0
		c1,1,1,2.6,0,3.5L19.4,55.7C18.9,56.2,18.3,56.4,17.6,56.4z" />
                                </g>
                            </svg>
                        </td>';
                            } else {
                                echo ' <td class="pricing-feature-cell ' . $s . '"  data-sid="' . $Plan['PlanID'] . '" >
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <path d="M35.4,32l19.9-19.9c1-1,1-2.4,0-3.4s-2.4-1-3.4,0L32,28.6L12,8.8c-0.9-1-2.4-1-3.3,0s-1,2.4,0,3.4L28.6,32L8.7,51.9
	c-1,1-1,2.4,0,3.4c0.5,0.4,1,0.7,1.7,0.7s1.2-0.2,1.7-0.7l20-19.9l20,19.8c0.5,0.4,1.2,0.7,1.7,0.7c0.5,0,1.2-0.2,1.7-0.7
	c1-1,1-2.4,0-3.4L35.4,32z"></path>
                            </svg>
                        </td>';
                            }
                            $n++;
                        }

                        ?>
                    </tr>
                </tbody>
            </table>

            <span class="plan-notice mt-3"><?= StaticFunctions::lang('316_hd-and-ultra-hd-availability-depends-on') ?></span>

            <div style="display: none;" id="ChangeBtn" class="mt-4 pl-3 pr-3 pl-sm-0 pr-sm-0 plan-footer">
                <a href="javascript:;" id="ButtonStep2" class="btn-plans"><?= StaticFunctions::lang('113_change') ?></a>
            </div>


        </div>

    </div>




</section>


<div style="max-width: 1024px;
    margin: 0 auto;
    display:none;
    padding: 40px 32px 50px;" id="LastArea">
    <h2 class="step-subtitle-larger text-center">
        <?= StaticFunctions::lang('317_your-plan-has-been-successfully') ?>
    </h2>
    <h2 class="step-subtitle-larger text-center">
        <?= StaticFunctions::lang('318_updated-if-you-have-a-remaining') ?>
    </h2>
    <h2 class="step-subtitle-larger text-center">
        <?= StaticFunctions::lang('319_your-new-plan-has-been-created-by') ?>
    </h2>
    <hr>
    <h2 class="step-subtitle-larger text-center">
        <?= StaticFunctions::lang('320_you-can-continue-watching-netflux-with') ?>
    </h2>
    <div class="mt-3"><a href="<?= PATH ?>browse" class="btn-plans"><?= StaticFunctions::lang('321_continue') ?></a>
    </div>
</div>


<input type="text" id="CurrentPlan" value="<?= $MyPacketID ?>" hidden />
<input type="text" id="PTexts" value="<?= StaticFunctions::lang('322_subscribe-with') ?>" hidden />

<div style="display: none;" class="tingle-bil">
    <div class="row">
        <div class="col-12 text-center">
            <h3><?= StaticFunctions::lang('323_update-your') ?></h3>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-12 text-center">
            <h5><?= StaticFunctions::lang('324_you-switched-from-a-free-plan-or-a') ?>
            </h5>
            <h5><?= StaticFunctions::lang('325_please-subscribe-to-your-new-plan-with') ?>
            </h5>
        </div>
        <hr>
    </div>
    <hr>
    <div class="row mt-4">

        <div class="col-6">
            <div class="mt-2 paypal-button-container">
                <a onclick="PayWith('paypal');" style="background-color: #013088;" href="javascript:;"
                    class="btn-plans rounded-4 P1"><img style="    width: 20px;
    margin-top: -5px;
    margin-right: 4px;"
                        src="/assets/media/paypal.png" /><?= StaticFunctions::lang('424_continue-with', ['margin-right:2px;']) ?></a>
            </div>
        </div>

        <div class="col-6">
            <div class="mt-2 StripeDiv">
                <a onclick="PayWith('stripe');" style="background-color: #4f566b;" href="javascript:;"
                    class="btn-plans rounded-4 P2"><img style="    width: 30px;
    margin-top: -3px;
    margin-right: -1px;"
                        src="/assets/media/stripe.png" /><?= StaticFunctions::lang('425_continue-with', ['margin-right:2px;']) ?></a>
            </div>
        </div>

    </div>


</div>

<?php

$BillingClass = new NetfluxBilling();
$BillingClass->setDb($db);
$ClientID = $BillingClass->PaypalClientID();

?>
<input type="text" hidden id="PaypalUrl" value="https://www.paypal.com/sdk/js?client-id=<?= $ClientID ?>&vault=true" />

<?php

require_once StaticFunctions::View('V' . '/classic.footer.php');