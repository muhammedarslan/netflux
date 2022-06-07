    <?php

    $PageCss = [];
    $PageJs = [
        '/assets/netflux/js/login.js'
    ];

    $LoginCode = (isset($Params[0])) ? $Params[0] : null;
    $InputVal = '';

    if ($LoginCode != null) {
        try {
            $DecodedHash = \Firebase\JWT\JWT::decode($LoginCode, StaticFunctions::JwtKey(), array('HS256'));
            $InputVal = $DecodedHash->LoginEmail;
        } catch (\Throwable $th) {
        }
    }


    require_once StaticFunctions::View('V' . '/classic.header.php');

    ?>
    <style>
        .form-control.is-invalid,
        .was-validated .form-control:invalid {
            border: none;
            border-bottom: 2.5px solid #ffa00a;
            background-image: none;
            border-radius: 0px !important;
        }

        .invalid-feedback {
            color: #ffa00a;
        }

        .form-control.is-invalid:focus,
        .was-validated .form-control:invalid:focus {
            border-bottom-color: #ffa00a;
            box-shadow: none;
            border-radius: 0px !important;
        }
    </style>
    <section class="box border-0">
        <div class="box-background position-absolute login_background">
            <img src="<?= PATH ?>assets/netflux/images/background.jpg" alt="Netflux Background">
        </div>

        <div class="login-content d-flex justify-content-center align-items-center">
            <div class="form_background">
                <div class="d-flex flex-column login login_d">
                    <div class="d-flex flex-column justify-content-center">
                        <h1 class="login-title mb-4"><?= StaticFunctions::lang('382_sign') ?></h1>
                    </div>
                    <form method="POST" action="javascript:;" novalidate id="LoginForm">
                        <div style="text-align:center;" id="AjaxContent">
                        </div>
                        <div class="login-form mb-3">
                            <div style="height: 50px;" class="input-group form-login form-field">
                                <input data-button-id="form_button" data-error-text="<?= StaticFunctions::lang('442_please-enter-a-valid-e-mail') ?>" autocomplete="off" novalidate disabled value="<?= $InputVal ?>" name="email" type="email" id="form_email" class="form-control form-input">
                                <label id="LabelEmail" for="form_email"><?= StaticFunctions::lang('365_e-mail') ?></label>
                            </div>
                        </div>
                        <div style="margin-top: 30px;" class="login-form mb-3">
                            <div style="height: 50px;" class="input-group form-login form-field" id="form-login">
                                <input data-button-id="form_button" data-error-text="<?= StaticFunctions::lang('443_please-enter-a-password-of-at-least-5') ?>" autocomplete="off" novalidate disabled name="password" type="password" id="form_password" class="form-control form-input-pass">
                                <label id="LabelPass" for="form_password"><?= StaticFunctions::lang('337_password') ?></label>
                                <button type="button" class="show-hide-button" id="showhide" onclick="showhidetoggle()"><?= StaticFunctions::lang('383_show') ?></button>
                                <button style="display: 
                            none;" type="button" class="show-hide-button" id="showhide2" onclick="showhidetoggle2()"><?= StaticFunctions::lang('384_hide') ?></button>
                            </div>
                        </div>
                        <div style="margin-top:30px;" class="">
                            <button type="submit" data-click-event="LoginFormSubmit();" id="form_button" class="btn-plans rounded-4"><?= StaticFunctions::lang('382_sign') ?></button>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <div class="login-checkbox">
                                <input name="remember_me" type="checkbox" id="checkbox_1" checked>
                                <label for="checkbox_1"><span><?= StaticFunctions::lang('218_remember') ?></span></label>
                            </div>
                            <div class="ml-auto"><a href="javascript:;" class="login-help"><?= StaticFunctions::lang('385_do-you-want-some') ?></a></div>
                        </div>
                    </form>
                    <div style="margin-bottom: 10px;" class="mt-4 facebook-login">
                        <a onclick="LoginWith('facebook');" href="javascript:;">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                <path d="M60.5,0H3.6C1.6,0,0,1.6,0,3.6v56.9c0,2,1.6,3.6,3.6,3.6h30.6V39.3h-8.3v-9.7h8.3v-7.1c0-8.3,5-12.8,12.4-12.8
	c2.5,0,5,0.1,7.4,0.4v8.6H49c-4,0-4.8,1.9-4.8,4.7v6.2h9.6l-1.2,9.7h-8.3V64h16.3c2,0,3.6-1.6,3.6-3.6V3.6C64,1.6,62.4,0,60.5,0z" />
                            </svg>
                            <span><?= StaticFunctions::lang('386_sign-in-with') ?></span>
                        </a>
                    </div>
                    <div class="mt-2 login-signup">
                        <?= StaticFunctions::lang('451_would-you-like-to-join') ?>
                        <a style="margin-left: 5px;" href="<?= PATH . LANG ?>">
                            <?= StaticFunctions::lang('387_register') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php

    require_once StaticFunctions::View('V' . '/classic.footer.php');

    ?>