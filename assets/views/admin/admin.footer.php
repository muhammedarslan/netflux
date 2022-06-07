</main>


<div style="margin-top:100px;" class="modal js-modal" data-name="first-modal2_profile">
    <div class="backdrop"></div>
    <div class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('188_my') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/admin/profile" action="javascript:;" action=""
            method="post">
            <div class="fields">

                <input hidden style="display: none;" type="text" name="profile_id" value="" />
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('189_name-amp') ?>
                        <input required name="profile_real_name" type="text" />

                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('190_e-mail') ?>
                        <input required name="profile_email" type="text" />

                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('191_account') ?>
                        <input name="password" placeholder="<?= StaticFunctions::lang('192_if-you-will-not-change-it-leave-it') ?>" type="text" />

                    </label>
                </div>


            </div>
            <button class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>


<?php

if ($FileName != 'login') {
    echo ' </div>
    </div>';
}



?>
<script>
const SomeTextLangs = <?= $SomeText ?>
</script>
<script src="<?= PATH ?>assets/console/js/jquery.js"></script>
<script src="<?= PATH ?>assets/console/js/slick.min.js"></script>
<script src="<?= PATH ?>assets/netflux/js/barba.js"></script>
<script src="<?= PATH ?>assets/netflux/js/lazyload.js"></script>
<script src="<?= PATH ?>assets/console/js/jquery.star-rating-svg.js"></script>
<script src="<?= PATH ?>assets/console/js/jquery.dataTables.min.js"></script>
<script src="<?= PATH ?>assets/console/js/sweetalert.min.js"></script>
<script src="<?= PATH ?>assets/netflux/js/topbar.min.js"></script>
<script src="<?= PATH ?>assets/console/js/toastr.min.js"></script>
<script src="<?= PATH ?>assets/console/js/main.js"></script>
<script src="<?= PATH ?>assets/console/js/core.js"></script>
</body>

</html>