<?php

$PageCss = [];
$PageJs = [
    '/assets/console/js/table.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');


?>

<div class="content">

    <div class="row">
        <div style="display: inline-block;" class="col-6">
            <h1><?= StaticFunctions::lang('207_languages') ?></h1>
        </div>
        <div style="display: inline-block;float:right;" class="col-6">
            <a href="javascript:;" class="button js-modal-toggle" data-target="first-modal"><?= StaticFunctions::lang('144_add') ?></a>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="languages" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('208_flag') ?></th>
                    <th><?= StaticFunctions::lang('209_language') ?></th>
                    <th><?= StaticFunctions::lang('210_language') ?></th>
                    <th><?= StaticFunctions::lang('147_actions') ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>


<div class="modal js-modal" data-name="first-modal">
    <div class="backdrop"></div>
    <div style="margin-top: 180px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('211_add-new') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/new/language" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('210_language') ?>
                        <input required name="lang_name" placeholder="<?= StaticFunctions::lang('212_name-of-the') ?>" type="text" />
                    </label>
                </div>
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('209_language') ?>
                        <input required name="lang_code" placeholder="<?= StaticFunctions::lang('213_language') ?>" type="text" />
                    </label>
                </div>
            </div>
            <button class="button form_button"><?= StaticFunctions::lang('150_add') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>

<div class="modal js-modal" data-name="first-modal2">
    <div class="backdrop"></div>
    <div style="margin-top: 180px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('214_edit') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/language" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('210_language') ?>
                        <input required name="edit_language_name" placeholder="<?= StaticFunctions::lang('212_name-of-the') ?>" type="text" />
                        <input hidden style="display: none;" type="text" name="edit_id" value="" />
                    </label>
                </div>
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('209_language') ?>
                        <input required name="edit_language_code" placeholder="<?= StaticFunctions::lang('213_language') ?>" type="text" />
                    </label>
                </div>
            </div>
            <button class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>


<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
