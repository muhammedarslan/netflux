<?php


$PageCss = [
    '/assets/netflux/css/tingle2.css'
];
$PageJs = [
    '/assets/netflux/js/tingle.js',
    '/assets/netflux/js/topbar.min.js',
    '/assets/netflux/js/account.js',
    '/assets/netflux/js/sweetalert2.all.min.js'
];

$TableTitle = StaticFunctions::lang('334_payment');
$TableID    = 'billing';
require_once StaticFunctions::View('V' . '/table.' . $TableID . '.php');

require_once StaticFunctions::View('V' . '/classic.header.php');

$Me = StaticFunctions::get_id();
$User = $db->query("SELECT * FROM users WHERE    id = '{$Me}' and status=1 ")->fetch(PDO::FETCH_ASSOC);
$Pi = $User['user_packet'];
$UserBilling = json_decode($User['user_extra'], true);
$MyPacket = $db->query("SELECT * FROM packets WHERE id='{$Pi}'  ")->fetch(PDO::FETCH_ASSOC);

if (!$User) {
    StaticFunctions::go_home();
    exit;
}

?>

<style>
    .top {
        display: none !important;
    }
</style>

<section id="AccountContainerSection" class="box border-bottom">
    <div style="pointer-events: none;opacity:0.5;" class="container account-container mt-5 mb-5">
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
                        <div class="account-section-item"><a onclick="ChangePassword();" href="javascript:;"><?= StaticFunctions::lang('338_change-my') ?></a></div>
                    </div>
                </div>
                <?php

                echo '<div class="account-section-group">
                    <div>
                        <div class="account-section-item account-section-item-email">
                        </div>
                    </div>           
                 </div>';

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
                        <div class="account-section-item account-section-item-plan">...</div>
                    </div>
                    <div class="text-right">
                    </div>
                </div>
            </div>
        </div>
</section>
<input id="CancelText" value="<?= StaticFunctions::lang('346_your-subscription-will-be-canceled-do') ?>" hidden />
<input id="CanceledText" value="<?= StaticFunctions::lang('347_it-is') ?>" hidden />
<input id="ChangePass" value="<?= StaticFunctions::lang('338_change-my') ?>" hidden />
<input id="ChangePass2" value="<?= StaticFunctions::lang('348_your-new') ?>" hidden />
<input id="ChangePass3" value="<?= StaticFunctions::lang('349_confirm-your') ?>" hidden />
<input id="ChangePass4" value="<?= StaticFunctions::lang('350_close') ?>" hidden />
<input id="CancelAccountText" value="<?= StaticFunctions::lang('351_we-are-sorry-that-you-want-to-cancel') ?>" hidden />


<?php

require_once StaticFunctions::View('V' . '/classic.footer.php');
