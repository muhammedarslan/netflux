<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();
$User = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
$Pi = $User['user_packet'];
$UserBilling = json_decode($User['user_extra'], true);
$MyPacket = $db->query("SELECT * FROM packets WHERE id='{$Pi}'  ")->fetch(PDO::FETCH_ASSOC);

if (!$User) {
    http_response_code(401);
    exit;
}

?>


<div class="container account-container mt-5 mb-5">
    <div class="account-title">
        <h1><?= StaticFunctions::lang('112_my') ?></h1>
    </div>
    <div class="divider"></div>
    <div class="row">
        <div class="col-lg-4 mt-3">
            <h2><?= StaticFunctions::lang('335_membership-amp') ?></h2>
            <button onclick="CancelAccount();" class="cancel-button"><?= StaticFunctions::lang('336_close-my') ?></button>
        </div>
        <div class="col">
            <div class="account-section-group">
                <div>
                    <div class="account-section-item account-section-item-email"><?= $User['email'] ?></div>
                    <div class="account-section-item"><?= StaticFunctions::lang('337_password') ?>: ********</div>
                </div>
                <div class="text-right">
                    <div class="account-section-item"><?= StaticFunctions::lang('426_we-have-been-together-with-great', [
                                                            StaticFunctions::timerFormat($User['created_time'], time())
                                                        ]) ?></div>
                    <div class="account-section-item"><a onclick="ChangePassword();"
                            href="javascript:;"><?= StaticFunctions::lang('338_change-my') ?></a></div>
                </div>
            </div>
            <?php
            $ShowOther = true;
            $OtherEl = false;
            if (isset($UserBilling['Paypal']) && $UserBilling['Paypal'] != '') :
                $ShowOther = false;
                echo '<div class="account-section-group">
                    <div>
                        <div class="account-section-item account-section-item-email">
<svg xmlns="http://www.w3.org/2000/svg" width="124" height="33"><path fill="#253B80" d="M46.211 6.749h-6.839a.95.95 0 0 0-.939.802l-2.766 17.537a.57.57 0 0 0 .564.658h3.265a.95.95 0 0 0 .939-.803l.746-4.73a.95.95 0 0 1 .938-.803h2.165c4.505 0 7.105-2.18 7.784-6.5.306-1.89.013-3.375-.872-4.415-.972-1.142-2.696-1.746-4.985-1.746zM47 13.154c-.374 2.454-2.249 2.454-4.062 2.454h-1.032l.724-4.583a.57.57 0 0 1 .563-.481h.473c1.235 0 2.4 0 3.002.704.359.42.469 1.044.332 1.906zM66.654 13.075h-3.275a.57.57 0 0 0-.563.481l-.145.916-.229-.332c-.709-1.029-2.29-1.373-3.868-1.373-3.619 0-6.71 2.741-7.312 6.586-.313 1.918.132 3.752 1.22 5.031.998 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .562.66h2.95a.95.95 0 0 0 .939-.803l1.77-11.209a.568.568 0 0 0-.561-.658zm-4.565 6.374c-.316 1.871-1.801 3.127-3.695 3.127-.951 0-1.711-.305-2.199-.883-.484-.574-.668-1.391-.514-2.301.295-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.499.589.697 1.411.554 2.317zM84.096 13.075h-3.291a.954.954 0 0 0-.787.417l-4.539 6.686-1.924-6.425a.953.953 0 0 0-.912-.678h-3.234a.57.57 0 0 0-.541.754l3.625 10.638-3.408 4.811a.57.57 0 0 0 .465.9h3.287a.949.949 0 0 0 .781-.408l10.946-15.8a.57.57 0 0 0-.468-.895z"/><path fill="#179BD7" d="M94.992 6.749h-6.84a.95.95 0 0 0-.938.802l-2.766 17.537a.569.569 0 0 0 .562.658h3.51a.665.665 0 0 0 .656-.562l.785-4.971a.95.95 0 0 1 .938-.803h2.164c4.506 0 7.105-2.18 7.785-6.5.307-1.89.012-3.375-.873-4.415-.971-1.142-2.694-1.746-4.983-1.746zm.789 6.405c-.373 2.454-2.248 2.454-4.062 2.454h-1.031l.725-4.583a.568.568 0 0 1 .562-.481h.473c1.234 0 2.4 0 3.002.704.359.42.468 1.044.331 1.906zM115.434 13.075h-3.273a.567.567 0 0 0-.562.481l-.145.916-.23-.332c-.709-1.029-2.289-1.373-3.867-1.373-3.619 0-6.709 2.741-7.311 6.586-.312 1.918.131 3.752 1.219 5.031 1 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .564.66h2.949a.95.95 0 0 0 .938-.803l1.771-11.209a.571.571 0 0 0-.565-.658zm-4.565 6.374c-.314 1.871-1.801 3.127-3.695 3.127-.949 0-1.711-.305-2.199-.883-.484-.574-.666-1.391-.514-2.301.297-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.501.589.699 1.411.554 2.317zM119.295 7.23l-2.807 17.858a.569.569 0 0 0 .562.658h2.822c.469 0 .867-.34.939-.803l2.768-17.536a.57.57 0 0 0-.562-.659h-3.16a.571.571 0 0 0-.562.482z"/><path fill="#253B80" d="M7.266 29.154l.523-3.322-1.165-.027H1.061L4.927 1.292a.316.316 0 0 1 .314-.268h9.38c3.114 0 5.263.648 6.385 1.927.526.6.861 1.227 1.023 1.917.17.724.173 1.589.007 2.644l-.012.077v.676l.526.298a3.69 3.69 0 0 1 1.065.812c.45.513.741 1.165.864 1.938.127.795.085 1.741-.123 2.812-.24 1.232-.628 2.305-1.152 3.183a6.547 6.547 0 0 1-1.825 2c-.696.494-1.523.869-2.458 1.109-.906.236-1.939.355-3.072.355h-.73c-.522 0-1.029.188-1.427.525a2.21 2.21 0 0 0-.744 1.328l-.055.299-.924 5.855-.042.215c-.011.068-.03.102-.058.125a.155.155 0 0 1-.096.035H7.266z"/><path fill="#179BD7" d="M23.048 7.667c-.028.179-.06.362-.096.55-1.237 6.351-5.469 8.545-10.874 8.545H9.326c-.661 0-1.218.48-1.321 1.132L6.596 26.83l-.399 2.533a.704.704 0 0 0 .695.814h4.881c.578 0 1.069-.42 1.16-.99l.048-.248.919-5.832.059-.32c.09-.572.582-.992 1.16-.992h.73c4.729 0 8.431-1.92 9.513-7.476.452-2.321.218-4.259-.978-5.622a4.667 4.667 0 0 0-1.336-1.03z"/><path fill="#222D65" d="M21.754 7.151a9.757 9.757 0 0 0-1.203-.267 15.284 15.284 0 0 0-2.426-.177h-7.352a1.172 1.172 0 0 0-1.159.992L8.05 17.605l-.045.289a1.336 1.336 0 0 1 1.321-1.132h2.752c5.405 0 9.637-2.195 10.874-8.545.037-.188.068-.371.096-.55a6.594 6.594 0 0 0-1.017-.429 9.045 9.045 0 0 0-.277-.087z"/><path fill="#253B80" d="M9.614 7.699a1.169 1.169 0 0 1 1.159-.991h7.352c.871 0 1.684.057 2.426.177a9.757 9.757 0 0 1 1.481.353c.365.121.704.264 1.017.429.368-2.347-.003-3.945-1.272-5.392C20.378.682 17.853 0 14.622 0h-9.38c-.66 0-1.223.48-1.325 1.133L.01 25.898a.806.806 0 0 0 .795.932h5.791l1.454-9.225 1.564-9.906z"/></svg>
                            <span id="Span_Paypal" >' . $UserBilling['Paypal'] . '</span>
                        </div>
                    </div>
                    <div class="text-right Btn_Paypal">
                     <div class="account-section-item">Paypal</div>
                        <div class="account-section-item cancel_sub_element"><a href="javascript:;" onclick="CancelSub(\'Paypal\');" >' . StaticFunctions::lang('339_cancel') . '</a></div>
                    </div>
                </div>';
            endif;

            if (isset($UserBilling['Stripe']) && $UserBilling['Stripe'] != '') :
                $ShowOther = false;
                echo '<div class="account-section-group">
                    <div>
                        <div class="account-section-item account-section-item-email">
<svg style="margin-top:0px;" width="124" height="33" viewBox="0 0 512 214" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid">
    <g>
        <path d="M35.9822222,83.4844444 C35.9822222,77.9377778 40.5333333,75.8044444 48.0711111,75.8044444 C58.88,75.8044444 72.5333333,79.0755556 83.3422222,84.9066667 L83.3422222,51.4844444 C71.5377778,46.7911111 59.8755556,44.9422222 48.0711111,44.9422222 C19.2,44.9422222 0,60.0177778 0,85.1911111 C0,124.444444 54.0444444,118.186667 54.0444444,135.111111 C54.0444444,141.653333 48.3555556,143.786667 40.3911111,143.786667 C28.5866667,143.786667 13.5111111,138.951111 1.56444444,132.408889 L1.56444444,166.257778 C14.7911111,171.946667 28.16,174.364444 40.3911111,174.364444 C69.9733333,174.364444 90.3111111,159.715556 90.3111111,134.257778 C90.1688889,91.8755556 35.9822222,99.4133333 35.9822222,83.4844444 Z M132.124444,16.4977778 L97.4222222,23.8933333 L97.28,137.813333 C97.28,158.862222 113.066667,174.364444 134.115556,174.364444 C145.777778,174.364444 154.311111,172.231111 159.004444,169.671111 L159.004444,140.8 C154.453333,142.648889 131.982222,149.191111 131.982222,128.142222 L131.982222,77.6533333 L159.004444,77.6533333 L159.004444,47.36 L131.982222,47.36 L132.124444,16.4977778 Z M203.235556,57.8844444 L200.96,47.36 L170.24,47.36 L170.24,171.804444 L205.795556,171.804444 L205.795556,87.4666667 C214.186667,76.5155556 228.408889,78.5066667 232.817778,80.0711111 L232.817778,47.36 C228.266667,45.6533333 211.626667,42.5244444 203.235556,57.8844444 Z M241.493333,47.36 L277.191111,47.36 L277.191111,171.804444 L241.493333,171.804444 L241.493333,47.36 Z M241.493333,36.5511111 L277.191111,28.8711111 L277.191111,0 L241.493333,7.53777778 L241.493333,36.5511111 Z M351.431111,44.9422222 C337.493333,44.9422222 328.533333,51.4844444 323.555556,56.0355556 L321.706667,47.2177778 L290.417778,47.2177778 L290.417778,213.048889 L325.973333,205.511111 L326.115556,165.262222 C331.235556,168.96 338.773333,174.222222 351.288889,174.222222 C376.746667,174.222222 399.928889,153.742222 399.928889,108.657778 C399.786667,67.4133333 376.32,44.9422222 351.431111,44.9422222 Z M342.897778,142.933333 C334.506667,142.933333 329.528889,139.946667 326.115556,136.248889 L325.973333,83.4844444 C329.671111,79.36 334.791111,76.5155556 342.897778,76.5155556 C355.84,76.5155556 364.8,91.0222222 364.8,109.653333 C364.8,128.711111 355.982222,142.933333 342.897778,142.933333 Z M512,110.08 C512,73.6711111 494.364444,44.9422222 460.657778,44.9422222 C426.808889,44.9422222 406.328889,73.6711111 406.328889,109.795556 C406.328889,152.604444 430.506667,174.222222 465.208889,174.222222 C482.133333,174.222222 494.933333,170.382222 504.604444,164.977778 L504.604444,136.533333 C494.933333,141.368889 483.84,144.355556 469.76,144.355556 C455.964444,144.355556 443.733333,139.52 442.168889,122.737778 L511.715556,122.737778 C511.715556,120.888889 512,113.493333 512,110.08 Z M441.742222,96.5688889 C441.742222,80.4977778 451.555556,73.8133333 460.515556,73.8133333 C469.191111,73.8133333 478.435556,80.4977778 478.435556,96.5688889 L441.742222,96.5688889 L441.742222,96.5688889 Z" fill="#6772E5"></path>
    </g>
</svg>

                            <span id="Span_Stripe" >' . $UserBilling['Stripe'] . '</span>
                        </div>
                    </div>
                    <div class="text-right Stripe">
                    <div class="account-section-item">Stripe</div>
                        <div class="account-section-item cancel_sub_element"><a href="javascript:;" onclick="CancelSub(\'Stripe\');" >' . StaticFunctions::lang('339_cancel') . '</a></div>
                    </div>
                </div>';
            endif;

            if ($ShowOther && $MyPacket['packet_price'] < 1) :
                $N = time();
                $ShowOther = false;
                echo '<div class="account-section-group">
                    <div>
                        <div class="account-section-item account-section-item-email">
                        <img width="120px" src="/assets/netflux/images/logo.png"/>
                            <span>' . StaticFunctions::lang('340_free') . '</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div style="margin-top:5px;" class="account-section-item"><a href="' . PATH . 'account/packets"
                >' . StaticFunctions::lang('341_upgrade-my') . '</a>
            </div>
        </div>
    </div>';
            endif;

            $N = time();
            $CheckTrial = $db->query("SELECT * FROM payments WHERE user_id = '{$Me}' and payment_type='trial' and payment_finish_time > '{$N}' ")->fetch(PDO::FETCH_ASSOC);
            if ($CheckTrial && $MyPacket['packet_price'] > 0) {
                $ShowOther = false;
                echo '<div class="account-section-group">
                    <div>
                        <div class="account-section-item account-section-item-email">
                        <img width="120px" src="/assets/netflux/images/logo.png"/>
                            <span style="font-weight:500;color:#757575;" >' . StaticFunctions::lang('342_your-remaining-trial') . ' <strong>' . StaticFunctions::timerFormat(time(), $CheckTrial['payment_finish_time']) . '</strong> </span>
                        </div>
                    </div>
    </div>';
            }

            if ($ShowOther) :
                $ShowOther = false;
                $OtherEl = true;
                echo '<div class="account-section-group">
                    <div>
                        <div class="account-section-item account-section-item-email">
                        <img width="120px" src="/assets/netflux/images/logo.png"/>
                        </div>
                    </div>           
                 </div>';
            endif;

            ?>

        </div>
    </div>
    <div class="divider"></div>
    <div class="row">
        <div class="col-md-3 mt-3">
            <h2><?= StaticFunctions::lang('343_plan') ?></h2>
        </div>
        <div class="col">
            <div class="account-section-group">
                <div>
                    <div class="account-section-item account-section-item-plan">
                        <?php echo StaticFunctions::PacketTranslation($MyPacket['packet_name'], $MyPacket['packet_translations']);

                        if ($MyPacket['packet_price'] > 0 && $OtherEl == true) {
                            echo ' ' . StaticFunctions::lang('444_payment');
                        }

                        ?></div>
                </div>
                <div class="text-right">
                    <div class="account-section-item"><a
                            href="<?= PATH ?>account/packets"><?= StaticFunctions::lang('344_change') ?></a>
                        <div class="account-section-item"><a href="javascript:;"
                                onclick="BillingModal('All');"><?= StaticFunctions::lang('345_subscription') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="divider"></div>
    <div class="row">
        <div class="col-md-3 mt-3">
            <h2><?= StaticFunctions::lang('453_settings') ?></h2>
        </div>
        <div class="col">
            <div class="account-section-group">
                <div>
                    <div class="account-section-item ds_sns">
                        <a id="DestroySessionsT1" href="javascript:;"
                            onclick="DestroySessions();"><?= StaticFunctions::lang('452_end-session-on-all') ?></a>


                        <div id="DestroySessionsT2" style="font-style: italic;display:none;"
                            class="account-section-item"><?= Staticfunctions::lang('454_sessions-on-all-devices-have-been') ?></div>

                    </div>
                </div>

            </div>
        </div>
    </div>