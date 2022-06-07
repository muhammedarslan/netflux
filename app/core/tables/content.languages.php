<?php

//sleep(1);
$DataArray = [];
$GetData = [];

$Languages = AppLanguage::GetAllowedLangs();
$Me = StaticFunctions::get_id();

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
    array_push($DataArray, [
        ' <span class="Location">' . $row['id'] . '</span>',
        '<img style="width:50px;" src="' . PATH . 'assets/media/flags/' . mb_strtolower($ex[1]) . '.svg" alt="">',
        '<span class="Location">' . StaticFunctions::say($row['language_code']) . '</span>',
        '<span class="Location">' . StaticFunctions::say($row['language_name']) . '</span>',
        '<div onclick="OpenDropdownR(this); return false;" class="dropdown js2-dropdown">
                            <div class="toggle">...</div>
                            <ul>
                                <li>
                                    <a href="' . PATH . 'admin/languages/translate/' . $row['language_code'] . '" >' . StaticFunctions::lang('125_translate') . '</a>
                                </li>
                                <li>
                                    <a class="js-modal-toggle" onclick="EditLang(this);" data-edit="' . $Cd . '" data-target="first-modal2" href="javascript:;">' . StaticFunctions::lang('120_edit') . '</a>
                                </li>
                                <li>
                                    <a onclick="DeleteLang(\'Lang\',\'' . $Cd . '\');" href="javascript:;">' . StaticFunctions::lang('121_delete') . '</a>
                                </li>
                            </ul>
                        </div>'

    ]);
}