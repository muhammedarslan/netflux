<?php

//sleep(1);
$DataArray = [];

$Me = StaticFunctions::get_id();

$GetData = $db->query("SELECT * FROM currencies order by id DESC", PDO::FETCH_ASSOC);
if ($GetData->rowCount()) {
    foreach ($GetData as $row) {
        array_push($DataArray, [
            ' <span class="Location">' . $row['id'] . '</span>',
            '<span class="name">' . StaticFunctions::say($row['currency_name'] . ' - ' . $row['currency_code']) . '</span>',
            '<span class="Location">' . StaticFunctions::say($row['currency_symbol']) . '</span>',
            '<span class="Location">' . number_format($row['exchange_rate'], 2) . '</span>',
            '<div onclick="OpenDropdownR(this); return false;" class="dropdown js2-dropdown">
                            <div class="toggle">...</div>
                            <ul>
                                <li>
                                    <a class="js-modal-toggle" onclick="EditData(this);" data-edit="currencies-' . $row['id'] . '" data-target="first-modal2" href="javascript:;">' . StaticFunctions::lang('120_edit') . '</a>
                                </li>
                                <li>
                                    <a onclick="DeleteData(\'Currency\',' . $row['id'] . ');" href="javascript:;">' . StaticFunctions::lang('121_delete') . '</a>
                                </li>
                            </ul>
                        </div>'

        ]);
    }
}