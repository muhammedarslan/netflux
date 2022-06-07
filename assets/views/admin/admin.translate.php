<?php

$PageCss = [];
$PageJs = [
    '/assets/console/js/table.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');

$LangCode = $_Params[0];
$Languages = AppLanguage::GetAllowedLangs();
$Exp = explode('_', $LangCode);
$Cd = mb_strtolower($Exp[1]);

if (!isset($Languages[$Cd])) {
    StaticFunctions::go('admin');
}


?>

<div class="content">


    <div style="background-color: #ffffff;min-height:530px;padding:40px;margin-top:20px;border-radius:10px;position:relative;" class="s_form">
        <div class="backdrop"></div>
        <div class="modal-content">
            <form onsubmit="SubmitForm(this);" data-source="web-service/translate" action="javascript:;" action="" method="post">
                <div class="fields">
                    <input name="json_file" value="<?= $LangCode ?>" hidden />


                    <?php

                    $Original = AppLanguage::LanguageJson();
                    $LangArray = AppLanguage::LanguageSingleJson($Cd);

                    foreach ($Original as $key => $value) {
                        echo ' <div class="field">
                        <label>
                            ' . stripslashes(htmlspecialchars($Original[$key])) . '
                            <input value="' . stripslashes(htmlspecialchars($LangArray[$key])) . '" name="' . $key . '" type="text" />
                        </label>
                    </div>';
                    }

                    ?>

                </div>
                <button style="position: absolute;bottom:25px;right:25px;" class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
                <div style="clear:both"></div>
            </form>
        </div>
    </div>



</div>



<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
