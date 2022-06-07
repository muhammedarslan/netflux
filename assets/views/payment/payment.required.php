<?php

$Me = StaticFunctions::get_id();

$MyPacket = $db->query("SELECT * FROM packets INNER JOIN users on packets.id=users.user_packet WHERE users.id='{$Me}'  ")->fetch(PDO::FETCH_ASSOC);

$MyFirstProfile = $db->query("SELECT id from profiles WHERE user_id='{$Me}'")->fetch(PDO::FETCH_ASSOC);
if (!$MyFirstProfile) {
    require_once StaticFunctions::View('V' . '/payment.required.new.user.php');
    exit;
}

StaticFunctions::NoBarba();
$PageCss = [];
$PageJs = [
    '/assets/netflux/js/payment.required.js'
];

require_once StaticFunctions::View('V' . '/classic.header.php');

?>
<section class="box border-0">
    <div style="height:-webkit-fill-available;" class="box-background position-absolute login_background">
        <img src="<?= PATH ?>assets/netflux/images/background.jpg" alt="Netflux Background">
    </div>

    <div class="login-content d-flex justify-content-center align-items-center">
        <div style="height: 520px;" class="form_background">
            <div id="Content1" class="d-flex flex-column login login_d">
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="login-title mb-4 text-center">
                        <?= StaticFunctions::lang('428_to-avoid-losing-package', [StaticFunctions::PacketTranslation($MyPacket['packet_name'], $MyPacket['packet_translations'])]) ?>
                    </h1>

                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-4">
                            <img style="width: 100%;" src="<?= PATH ?>assets/media/calendar.png" />
                        </div>
                        <div style="margin-top: 6px;" class="col-4">
                            <span style="font-size: 27px;color:#ffffff;font-weight:600;text-align:center;">Her
                                Ay</span>
                            <span
                                style="font-size: 27px;color:#ffffff;font-weight:600;text-align:center;"><?= StaticFunctions::ShowPrice(StaticFunctions::FloatPrice($MyPacket['packet_price'] * UserCurrency['exchange_rate'], UserCurrency['rounding_type']), UserCurrency['currency_symbol'], UserCurrency['symbol_float']) ?></span>
                        </div>
                    </div>
                    <h3 style="font-size: 20px;margin-top:20px;" class="login-title mb-4 text-center">
                        <?= StaticFunctions::lang('394_you-need-to-update-your-payment') ?>
                    </h3>
                </div>

                <div id="paypal-button-container" class="mt-2">
                    <a id="P1" onclick="PayWith('paypal');" style="background-color: #013088;" href="javascript:;"
                        class="btn-plans rounded-4"><img style="    width: 20px;
    margin-top: -5px;
    margin-right: 4px;"
                            src="/assets/media/paypal.png" /><?= StaticFunctions::lang('424_continue-with', ['margin-right:2px;']) ?></a>
                </div>

                <div id="StripeDiv" class="mt-4">
                    <a id="P2" onclick="PayWith('stripe');" style="background-color: #4f566b;" href="javascript:;"
                        class="btn-plans rounded-4"><img style="    width: 30px;
    margin-top: -3px;
    margin-right: -1px;"
                            src="/assets/media/stripe.png" /><?= StaticFunctions::lang('425_continue-with', ['margin-right:2px;']) ?></a>
                </div>



            </div>

            <div style="display: none !important;" id="Content2" class="d-flex flex-column login login_d">
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="login-title mb-4 text-center">
                        <?= StaticFunctions::lang('395_please') ?>
                    </h1>
                </div>

                <div id="Content2Paypal" style="display: none;" class="mt-2">
                    <a style="background-color: #013088;" href="javascript:;"
                        class="btn-plans rounded-4 m-progress"><img style="    width: 20px;
    margin-top: -5px;
    margin-right: 4px;"
                            src="/assets/media/paypal.png" /><?= StaticFunctions::lang('424_continue-with', ['margin-right:2px;']) ?></a>
                </div>

                <div id="Content2Stripe" style="display: none;" class="mt-4">
                    <a style="background-color: #6e2656;" href="javascript:;"
                        class="btn-plans rounded-4 m-progress"><img style="    width: 20px;
    margin-top: -3px;
    margin-right: 4px;"
                            src="/assets/media/stripe.png" /><?= StaticFunctions::lang('425_continue-with', ['margin-right:2px;']) ?></a>
                </div>



            </div>


        </div>
    </div>
</section>

<?php

$BillingClass = new NetfluxBilling();
$BillingClass->setDb($db);
$ClientID = $BillingClass->PaypalClientID();

?>

<script src="https://www.paypal.com/sdk/js?client-id=<?= $ClientID ?>&vault=true"
    data-sdk-integration-source="button-factory"></script>
<script src="https://js.stripe.com/v3/"></script>



<?php
require_once StaticFunctions::View('V' . '/classic.footer2.php');

?>