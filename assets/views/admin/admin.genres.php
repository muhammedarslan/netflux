<?php

$PageCss = [];
$PageJs = [
    '/assets/console/js/table.js',
    '/assets/console/js/genres.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');


?>

<div class="content">

    <div class="row">
        <div style="display: inline-block;" class="col-6">
            <h1><?= StaticFunctions::lang('103_species') ?></h1>
        </div>
        <div style="display: inline-block;float:right;" class="col-6">
            <a href="javascript:;" class="button js-modal-toggle" data-target="first-modal"><?= StaticFunctions::lang('144_add') ?></a>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="genres" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('193_type') ?></th>
                    <th><?= StaticFunctions::lang('194_type') ?></th>
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
        <div class="hero"><?= StaticFunctions::lang('195_add-new') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/new/genres" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('193_type') ?>
                        <input required name="genres_name" placeholder="<?= StaticFunctions::lang('196_type') ?>" type="text" />
                    </label>
                </div>
            </div>
            <button class="button form_button"><?= StaticFunctions::lang('150_add') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>


<div class="modal js-modal" data-name="translate_genre">
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
                <form onsubmit="SubmitForm(this);" data-source="web-service/translate/genre" action="javascript:;" action="" method="post">

                    <div class="fields">
                        <div class="field">
                            <label>
                                <?= StaticFunctions::lang('196_type') ?>
                                <input disabled name="translate_original_title" placeholder="<?= StaticFunctions::lang('196_type') ?>" type="text" />
                            </label>
                        </div>
                    </div>

                    <input hidden style="display: none;" type="text" name="translate_genre_id" value="" />

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
                
                <form onsubmit="SubmitForm(this);" data-source="web-service/translate/genre?' . $LangCode . '" action="javascript:;" method="post">

                    <div class="fields">
                        <div class="field">
                            <label>
                                ' . StaticFunctions::lang('196_type') . '
                                <input data-translate-title="' . $Cd . '" name="translate_title" placeholder="' . StaticFunctions::lang('196_type') . '" type="text" />
                            </label>
                        </div>


                    </div>

                    <input hidden style="display: none;" type="text" name="translate_lang_code" value="' . $LangCode . '" />
                    <input hidden style="display: none;" type="text" name="translate_genre_id" value="" />

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
    <div style="margin-top: 180px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('197_edit') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/genres" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('193_type') ?>
                        <input required name="edit_genres_name" placeholder="<?= StaticFunctions::lang('196_type') ?>" type="text" />
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
