<section class="box border-bottom">

    <div class="signup-content d-flex justify-content-center align-items-center">
        <div class="d-flex flex-column signup-form">
            <div class="">
                <span class="step"><?= StaticFunctions::lang('409_step') ?></span>
            </div>
            <h1 class="step-title"><?= StaticFunctions::lang('412_create-a-password-to-start-your') ?></h1>
            <h2 class="step-subtitle-larger"><?= StaticFunctions::lang('413_this-is-the-last-step-you-have') ?></h2>
            <h2 class="step-subtitle-larger"><?= StaticFunctions::lang('414_you-will-then-be') ?></h2>
            <div style="height:75px" class="step-form">
                <?php

                StaticFunctions::new_session();
                $jwt = (isset($_SESSION['AppR'])) ? StaticFunctions::clear($_SESSION['AppR']) : null;
                $Email = '';
                if ($jwt != null) :
                    try {
                        $DecodedHash = \Firebase\JWT\JWT::decode($jwt, StaticFunctions::JwtKey(), array('HS256'));
                        $Email = $DecodedHash->RegisterEmail;
                    } catch (\Throwable $th) {
                        $Email = '';
                    }
                endif;
                ?>
                <div style="height: 60px;" class="input-group form-signup form-field">
                    <input data-button-id="ButtonStep4" data-error-text="<?= StaticFunctions::lang('442_please-enter-a-valid-e-mail') ?>"
                        autocomplete="off" novalidate type="email" id="email" value="<?= $Email ?>"
                        class="form-control step-input">
                    <label id="LabelEmail" for="email"><?= StaticFunctions::lang('415_e-mail') ?></label>
                </div>
            </div>
            <div style="height:75px" class="step-form">
                <div style="height: 60px;" class="input-group form-signup form-field">
                    <input data-button-id="ButtonStep4" data-error-text="<?= StaticFunctions::lang('443_please-enter-a-password-of-at-least-5') ?>"
                        autocomplete="off" novalidate minlength="5" type="password" id="password"
                        class="form-control step-input">
                    <label id="LabelPass" for="password"><?= StaticFunctions::lang('416_set-a') ?></label>
                </div>
            </div>
            <div class="email-checkbox">
                <input type="checkbox" id="checkbox_1">
                <label for="checkbox_1"><?= StaticFunctions::lang('417_do-not-email-netflux-special') ?></label>
            </div>

            <div class="mt-3"><a href="javascript:;" id="ButtonStep4" data-click-event="Button4Click();"
                    class="btn-plans"><?= StaticFunctions::lang('418_create-my') ?></a></div>
        </div>

        <div style="display: none;" id="LastArea">
            <h2 class="step-subtitle-larger text-center">
                <?= StaticFunctions::lang('419_your-account-has-been-created') ?></h2>
            <div class="mt-3"><a href="javascript:;" id="ButtonStep4"
                    class="btn-plans m-progress"><?= StaticFunctions::lang('419_your-account-has-been-created') ?></a>
            </div>
        </div>

    </div>
</section>