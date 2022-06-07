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
            <h1><?= StaticFunctions::lang('102_users') ?></h1>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="users" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('257_user') ?></th>
                    <th><?= StaticFunctions::lang('304_user') ?></th>
                    <th><?= StaticFunctions::lang('259_user') ?></th>
                    <th><?= StaticFunctions::lang('305_last') ?></th>
                    <th><?= StaticFunctions::lang('262_status') ?></th>
                    <th><?= StaticFunctions::lang('120_edit') ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>



<div class="modal js-modal" data-name="first-modal2">
    <div class="backdrop"></div>
    <div class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('306_edit') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/user" action="javascript:;" action="" method="post">
            <div class="fields">

                <input hidden style="display: none;" type="text" name="edit_id" value="" />
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('307_name-last') ?>
                        <input required name="edit_real_name" type="text" />

                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('190_e-mail') ?>
                        <input required name="edit_email" type="text" />

                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('191_account') ?>
                        <input name="password" placeholder="<?= StaticFunctions::lang('192_if-you-will-not-change-it-leave-it') ?>" type="text" />

                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('304_user') ?>
                        <select name="edit_user_type">
                            <option value="classic"><?= StaticFunctions::lang('135_member') ?></option>
                            <option value="admin"><?= StaticFunctions::lang('134_manager') ?></option>
                        </select>

                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('259_user') ?>
                        <select name="edit_user_packet">
                            <?php
                            $Packets = $db->query("SELECT * FROM packets", PDO::FETCH_ASSOC);
                            if ($Packets->rowCount()) {
                                foreach ($Packets as $row) {
                                    echo '<option value="' . $row['id'] . '">' . StaticFunctions::say($row['packet_name']) . '  ($' . number_format($row['packet_price'], 2) . ')</option>';
                                }
                            }
                            ?>
                        </select>

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
