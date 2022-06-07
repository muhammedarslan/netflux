<?php

$PageCss = [];
$PageJs = [
    '/assets/console/js/table.js',
    '/assets/console/js/packets.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');


?>

<div class="content">

    <div class="row">
        <div style="display: inline-block;" class="col-6">
            <h1><?= StaticFunctions::lang('461_currencies') ?></h1>
        </div>
        <div style="display: inline-block;float:right;" class="col-6">
            <a href="javascript:;" class="button js-modal-toggle" data-target="first-modal"><?= StaticFunctions::lang('144_add') ?></a>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="currencies" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('462_currency') ?></th>
                    <th><?= StaticFunctions::lang('463_currency') ?></th>
                    <th><?= StaticFunctions::lang('464_process') ?></th>
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
        <div class="hero"><?= StaticFunctions::lang('461_currencies') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/new/currency" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('462_currency') ?>
                        <select id="currenyCode" onchange="CurrencyChangeEvent();" name="currency_code" required>
                            <option value="" selected disabled><?= Staticfunctions::lang('465_select') ?></option>
                            <?php

                            $CurrencyList = json_decode(file_get_contents(APP_DIR . '/languages/currencies.json', true));

                            foreach ($CurrencyList as $key => $row) {
                                $row = (array) $row;
                                echo '<option value="' . $row['code'] . '" >' . StaticFunctions::say($row['code'] . ' - ' . $row['name']) . '</option>';
                            }

                            ?>
                        </select>
                    </label>
                </div>
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('464_process') ?>
                        <input required name="exchange_rate" placeholder="<?= StaticFunctions::lang('464_process') ?>" type="number" min="0.01" step="0.01" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('469_icon') ?>
                        <select name="symbol_position">
                            <option value="r"><?= Staticfunctions::lang('470_align') ?></option>
                            <option value="l"><?= Staticfunctions::lang('471_align') ?></option>
                        </select>
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('482_round-the') ?>
                        <select name="round_price">
                            <option value="0"><?= Staticfunctions::lang('483_rounding-the') ?></option>
                            <option value="1"><?= Staticfunctions::lang('484_round-the-fee-to-a-whole') ?></option>
                            <option value="2"><?= Staticfunctions::lang('485_round-the-fee-to-the-whole-number-and') ?></option>
                            <option value="3"><?= Staticfunctions::lang('486_round-up-to-99-cents-end-of') ?></option>
                            <option value="4"><?= Staticfunctions::lang('487_round-down-to-99-cents-end-of') ?></option>
                            <option value="5"><?= Staticfunctions::lang('488_round-up-to-99-cents-end-of') ?></option>
                        </select>
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
        <div class="hero"><?= StaticFunctions::lang('468_edit') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/currency" action="javascript:;" action="" method="post">
            <div class="fields">

                <div class="field">
                    <label>
                        <input hidden style="display: none;" type="text" name="edit_id" value="" />
                        <?= StaticFunctions::lang('464_process') ?>
                        <input required name="edit_exchange_rate" placeholder="<?= StaticFunctions::lang('464_process') ?>" type="number" min="0.01" step="0.01" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('469_icon') ?>
                        <select name="edit_symbol_float">
                            <option value="r"><?= Staticfunctions::lang('470_align') ?></option>
                            <option value="l"><?= Staticfunctions::lang('471_align') ?></option>
                        </select>
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('482_round-the') ?>
                        <select name="edit_rounding_type">
                            <option value="0"><?= Staticfunctions::lang('483_rounding-the') ?></option>
                            <option value="1"><?= Staticfunctions::lang('484_round-the-fee-to-a-whole') ?></option>
                            <option value="2"><?= Staticfunctions::lang('485_round-the-fee-to-the-whole-number-and') ?></option>
                            <option value="3"><?= Staticfunctions::lang('486_round-up-to-99-cents-end-of') ?></option>
                            <option value="4"><?= Staticfunctions::lang('487_round-down-to-99-cents-end-of') ?></option>
                            <option value="5"><?= Staticfunctions::lang('488_round-up-to-99-cents-end-of') ?></option>
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
