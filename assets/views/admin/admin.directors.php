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
            <h1><?= StaticFunctions::lang('101_directors') ?></h1>
        </div>
        <div style="display: inline-block;float:right;" class="col-6">
            <a href="javascript:;" class="button js-modal-toggle" data-target="first-modal"><?= StaticFunctions::lang('144_add') ?></a>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="directors" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('183_director') ?></th>
                    <th><?= StaticFunctions::lang('184_director') ?></th>
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
        <div class="hero"><?= StaticFunctions::lang('185_add-new') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/new/director" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('183_director') ?>
                        <input required name="director_name" placeholder="<?= StaticFunctions::lang('186_director-s') ?>" type="text" />
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
        <div class="hero"><?= StaticFunctions::lang('187_edit') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/director" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('183_director') ?>
                        <input required name="edit_director_name" placeholder="<?= StaticFunctions::lang('186_director-s') ?>" type="text" />
                        <input hidden style="display: none;" type="text" name="edit_id" value="" />
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
