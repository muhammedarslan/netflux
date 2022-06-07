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
            <h1><?= StaticFunctions::lang('104_plans') ?></h1>
        </div>
        <div style="display: inline-block;float:right;" class="col-6">
            <a onclick="TrialModalOpen();" style="margin-right: 5px;width:210px;" href="javascript:;" class="button js-modal-toggle" data-target="trial-settings-modal"><?= StaticFunctions::lang('457_trial-period') ?></a>
            <a href="javascript:;" class="button js-modal-toggle" data-target="first-modal"><?= StaticFunctions::lang('144_add') ?></a>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="plans" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('263_plan') ?></th>
                    <th><?= StaticFunctions::lang('264_plan') ?></th>
                    <th><?= StaticFunctions::lang('265_maximum-number-of') ?></th>
                    <th><?= StaticFunctions::lang('455_maximum-number-of') ?></th>
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
    <div style="margin-top: 80px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('266_add-new') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/new/plan" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('263_plan') ?>
                        <input required name="plan_name" placeholder="<?= StaticFunctions::lang('267_name-of-the') ?>" type="text" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('268_plan-fee') ?>
                        <input required name="plan_price" placeholder="<?= StaticFunctions::lang('269_fee-for-the') ?>" type="number" min="0" step="0.01" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('265_maximum-number-of') ?>
                        <input required name="max_device" placeholder="<?= StaticFunctions::lang('265_maximum-number-of') ?>" type="number" min="1" step="1" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('455_maximum-number-of') ?>
                        <input required name="max_profile" placeholder="<?= StaticFunctions::lang('455_maximum-number-of') ?>" type="number" min="1" step="1" max="4" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('270_hd') ?>
                        <select name="s1">
                            <option value="1"><?= StaticFunctions::lang('244_yes') ?></option>
                            <option value="0"><?= StaticFunctions::lang('245_no') ?></option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('271_ultrahd') ?>
                        <select name="s2">
                            <option value="1"><?= StaticFunctions::lang('244_yes') ?></option>
                            <option value="0"><?= StaticFunctions::lang('245_no') ?></option>
                        </select>
                    </label>
                </div>
            </div>

            <button class="button form_button"><?= StaticFunctions::lang('150_add') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>

<div class="modal js-modal" data-name="translate_packet">
    <div class="backdrop"></div>
    <div style="margin-top: 100px;" class="modal-content modal-content--wide js-tabs">
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <div class="left">
            <div class="navigation">
                <ul class="js-tab-nav">
                    <li>
                        <a id="FirstTabLink" href="javascript:;" class="active" data-target="tab_original"><?= Staticfunctions::lang('447_original') ?></a>
                    </li>
                    <?php

                    $DataArray = [];
                    $GetData = [];

                    $Languages = AppLanguage::GetAllowedLangs();
                    $N = 0;
                    foreach ($Languages as $key => $value) {
                        $N++;
                        array_push($GetData, [
                            'id' => $N,
                            'language_name' => $value['LangName'],
                            'language_code' => $value['LangFile'],
                        ]);
                    }


                    foreach ($GetData as $row) {
                        $ex = explode('_', $row['language_code']);
                        $Cd = mb_strtolower($ex[1]);
                        $LangCode = StaticFunctions::say($row['language_code']);
                        $LangName = StaticFunctions::say($row['language_name']);

                        echo '<li>
                        <a href="javascript:;" class="" data-target="tab_lang_' . $LangCode . '">' . $LangName . '</a>
                    </li>';
                    }

                    ?>
                </ul>
            </div>
        </div>
        <div class="right">




            <div class="js-tab-content active s_first" data-name="tab_original">

                <div class="hero"><?= StaticFunctions::lang('448_edit') ?></div>
                <a href="javascript:;" class="close js-modal-close">
                    <em class="fa fa-close"></em>
                </a>
                <form onsubmit="SubmitForm(this);" data-source="web-service/translate/packet" action="javascript:;" action="" method="post">

                    <div class="fields">
                        <div class="field">
                            <label>
                                <?= StaticFunctions::lang('263_plan') ?>
                                <input disabled name="translate_original_title" placeholder="<?= StaticFunctions::lang('263_plan') ?>" type="text" />
                            </label>
                        </div>
                    </div>

                    <input hidden style="display: none;" type="text" name="translate_packet_id" value="" />

                    <div style="clear:both"></div>
                </form>

            </div>

            <?php

            foreach ($GetData as $row) {
                $ex = explode('_', $row['language_code']);
                $Cd = mb_strtolower($ex[1]);
                $LangCode = StaticFunctions::say($row['language_code']);
                $LangName = StaticFunctions::say($row['language_name']);

                echo ' <div class="js-tab-content s_tabs" data-name="tab_lang_' . $LangCode . '">

                <div class="hero">' . StaticFunctions::lang('448_edit') . '<img style="width:35px;margin-left:10px;" src="/assets/media/flags/' . mb_strtolower($ex[1]) . '.svg"/></div>
                <a href="javascript:;" class="close js-modal-close">
                    <em class="fa fa-close"></em>
                </a>
                
                <form onsubmit="SubmitForm(this);" data-source="web-service/translate/packet?' . $LangCode . '" action="javascript:;" method="post">

                    <div class="fields">
                        <div class="field">
                            <label>
                                ' . StaticFunctions::lang('263_plan') . '
                                <input data-translate-title="' . $Cd . '" name="translate_title" placeholder="' . StaticFunctions::lang('263_plan') . '" type="text" />
                            </label>
                        </div>


                    </div>

                    <input hidden style="display: none;" type="text" name="translate_lang_code" value="' . $LangCode . '" />
                    <input hidden style="display: none;" type="text" name="translate_packet_id" value="" />

                    <button class="button">' . StaticFunctions::lang('120_edit') . '</button>
                    <div style="clear:both"></div>
                </form>

            </div>';
            }

            ?>





        </div>
    </div>
</div>

<div class="modal js-modal" data-name="first-modal2">
    <div class="backdrop"></div>
    <div style="margin-top: 80px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('272_edit') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/plan" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('263_plan') ?>
                        <input required name="edit_packet_name" placeholder="<?= StaticFunctions::lang('267_name-of-the') ?>" type="text" />
                    </label>
                </div>
            </div>
            <input hidden style="display: none;" type="text" name="edit_id" value="" />

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('268_plan-fee') ?>
                        <input required name="edit_packet_price" placeholder="<?= StaticFunctions::lang('269_fee-for-the') ?>" type="number" min="0" step="0.01" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('265_maximum-number-of') ?>
                        <input required name="edit_max_session_count" placeholder="<?= StaticFunctions::lang('265_maximum-number-of') ?>" type="number" min="1" step="1" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('455_maximum-number-of') ?>
                        <input required name="edit_max_profile_count" placeholder="<?= StaticFunctions::lang('455_maximum-number-of') ?>" type="number" min="1" max="4" step="1" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('270_hd') ?>
                        <select name="s1">
                            <option value="1"><?= StaticFunctions::lang('244_yes') ?></option>
                            <option value="0"><?= StaticFunctions::lang('245_no') ?></option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('271_ultrahd') ?>
                        <select name="s2">
                            <option value="1"><?= StaticFunctions::lang('244_yes') ?></option>
                            <option value="0"><?= StaticFunctions::lang('245_no') ?></option>
                        </select>
                    </label>
                </div>
            </div>
            <button class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>

<div class="modal js-modal" data-name="trial-settings-modal">
    <div class="backdrop"></div>
    <div style="margin-top: 180px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('457_trial-period') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/trial/settings" action="javascript:;" action="" method="post">
            <?php

            $GetRandomPacket = $db->query("SELECT trial_period from packets ")->fetch(PDO::FETCH_ASSOC);
            $TrialPeriod = number_format($GetRandomPacket['trial_period']);

            ?>


            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('458_trial-period') ?>
                        <input required name="trial_period" placeholder="<?= StaticFunctions::lang('458_trial-period') ?>" type="number" min="1" value="<?= $TrialPeriod ?>" max="30" step="1" />
                    </label>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('459_is-the-trial-period') ?>
                        <select name="trial_active">
                            <option <?php if ($TrialPeriod > 0) echo 'selected'; ?> value="1">
                                <?= StaticFunctions::lang('244_yes') ?></option>
                            <option <?php if ($TrialPeriod < 1) echo 'selected'; ?> value="0">
                                <?= StaticFunctions::lang('245_no') ?></option>
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
