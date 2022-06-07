<?php

$PageCss = [];
$PageJs = [
    '/assets/console/js/table.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');

$Set = $db->query("SELECT * FROM system_settings")->fetch(PDO::FETCH_ASSOC);

?>

<div class="content">

    <div class="row">
        <div style="display: inline-block;" class="col-6">
            <h1><?= StaticFunctions::lang('105_paypal-stripe-amp') ?></h1>
        </div>
        <div style="display: inline-block;float:right;" class="col-6">
        </div>
    </div>

    <br />

    <div style="background-color: #ffffff;min-height:630px;padding:40px;margin-top:20px;border-radius:10px;position:relative;" class="s_form">
        <div class="backdrop"></div>
        <div class="modal-content">
            <form onsubmit="SubmitForm(this);" data-source="web-service/billing/api" action="javascript:;" action="" method="post">
                <div class="fields">
                    <div class="field">
                        <label>
                            <?= StaticFunctions::lang('152_application') ?>
                            <select name="mode">
                                <option <?php if ($Set['app_mode'] == 'live') echo 'selected'; ?> value="live">
                                    <?= StaticFunctions::lang('153_real-live') ?></option>
                                <option <?php if ($Set['app_mode'] == 'sandbox') echo 'selected'; ?> value="sandbox">
                                    <?= StaticFunctions::lang('154_sandbox-test') ?></option>
                            </select>
                        </label>
                    </div>


                    <div class="field">
                        <label>
                            <?= StaticFunctions::lang('155_paypal-client') ?>
                            <input value="<?= $Set['paypal_id'] ?>" required name="paypal_id" type="text" />
                        </label>
                    </div>

                    <div class="field">
                        <label>
                            <?= StaticFunctions::lang('156_paypal-secret') ?>
                            <input value="<?= $Set['paypal_secret'] ?>" required name="paypal_secret" type="text" />
                        </label>
                    </div>

                    <div class="field">
                        <label>
                            <?= StaticFunctions::lang('157_stripe-client') ?>
                            <input value="<?= $Set['stripe_id'] ?>" required name="stripe_id" type="text" />
                        </label>
                    </div>

                    <div class="field">
                        <label>
                            <?= StaticFunctions::lang('158_stripe-secret') ?>
                            <input value="<?= $Set['stripe_secret'] ?>" required name="stripe_secret" type="text" />
                        </label>
                    </div>

                    <div class="field">
                        <label>
                            Facebook App Id
                            <input value="<?= $Set['facebook_app_id'] ?>" required name="facebook_app_id" type="text" />
                        </label>
                    </div>

                    <div style="width:90%" class="field">
                        <label>
                            Facebook App Secret
                            <input value="<?= $Set['facebook_app_secret'] ?>" required name="facebook_app_secret" type="text" />
                        </label>
                    </div>

                </div>
                <button style="position: absolute;bottom:42px;right:25px;" class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
                <div style="clear:both"></div>
            </form>
        </div>
    </div>



</div>



<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
