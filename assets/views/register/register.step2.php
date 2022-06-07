<?php

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


?>
<section class="box border-bottom">

    <div class="h-100 content">
        <div class="plan-container">
            <div class="pl-3 pr-3">
                <span class="step"><?= StaticFunctions::lang('403_step') ?></span>
                <h1 class="step-title"><?= StaticFunctions::lang('308_choose-the-plan-that-suits-you') ?></h1>
                <h2 class="step-subtitle">
                    <?= StaticFunctions::lang('408_upgrade-or-upgrade-whenever-you') ?></h2>
            </div>
            <div class="d-flex flex-column align-items-end mt-3 sticky-buttons">
                <div class="packages d-flex justify-content-around">
                    <?php
                    $n = 0;
                    foreach ($Plans as $key => $Plan) {
                        $s = '';
                        if ($n == 0) $s = 'checked';
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
                            if ($n == 0) $s = 'selected';
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
                            if ($n == 0) $s = 'selected';
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
                            if ($n == 0) $s = 'selected';
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
                            if ($n == 0) $s = 'selected';
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
                            if ($n == 0) $s = 'selected';
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
                            if ($n == 0) $s = 'selected';
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
                            if ($n == 0) $s = 'selected';
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

            <div class="mt-4 pl-3 pr-3 pl-sm-0 pr-sm-0 plan-footer">
                <a href="javascript:;" id="ButtonStep2" class="btn-plans"><?= StaticFunctions::lang('200_go') ?></a>
            </div>
        </div>
    </div>
</section>