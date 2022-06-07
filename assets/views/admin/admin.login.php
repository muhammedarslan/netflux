<?php
StaticFunctions::NoBarba();
$PageCss = [];
$PageJs = [
    '/assets/console/js/login.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');

?>
<style>
    .PureBlack {
        height: 700px !important;
    }
</style>
<div class="auth-screen">
    <div class="image">
    </div>
    <div class="content">
        <div class="hero">
            <span><?= StaticFunctions::lang('575-admin-login') ?> </span> <?= StaticFunctions::lang('2_welcome') ?>
        </div>
        <p><?= StaticFunctions::lang('215_you-can-login-with-your-administrator') ?></p>
        <form method="POST" action="javascript:;" id="LoginForm">
            <div style="text-align:center;" id="AjaxContent">
            </div>
            <div class="fields">
                <div class="field">
                    <label>
                        <em class="fa fa-user"></em>
                        <input required id="form_email" type="email" name="email" placeholder="<?= StaticFunctions::lang('216_admin') ?>" />
                    </label>
                </div>
                <div class="field">
                    <label>
                        <em class="fa fa-lock"></em>
                        <input required id="form_password" name="password" type="password" placeholder="<?= StaticFunctions::lang('217_admin') ?>" />
                    </label>
                </div>
            </div>
            <div class="actions">
                <label>
                    <input name="remember_me" type="checkbox" id="checkbox_1" />
                    <?= StaticFunctions::lang('218_remember') ?>
                </label>
            </div>
            <button type="submit" id="form_button" class="button">
                <em class="fa fa-lock"></em>
                <?= StaticFunctions::lang('219_login') ?>
            </button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>

<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
