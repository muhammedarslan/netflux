<?php

//sleep(1);
$DataArray = [];

$Me = StaticFunctions::get_id();

$GetData = $db->query("SELECT * FROM packets order by id DESC", PDO::FETCH_ASSOC);
if ($GetData->rowCount()) {
    foreach ($GetData as $row) {
        array_push($DataArray, [
            ' <span class="Location">' . $row['id'] . '</span>',
            '<span class="name">' . StaticFunctions::say($row['packet_name']) . '</span>',
            '<span class="Location">$' . number_format($row['packet_price'], 2) . '</span>',
            '<span class="Location">' . $row['max_session_count'] . ' ' . StaticFunctions::lang('133_pcs') . '</span>',
            '<span class="Location">' . $row['max_profile_count'] . ' ' . StaticFunctions::lang('456_pieces') . '</span>',
            '<div onclick="OpenDropdownR(this); return false;" class="dropdown js2-dropdown">
                            <div class="toggle">...</div>
                            <ul>
                            <li>
                                    <a class="js-modal-toggle" onclick="TranslatePacket(this);" data-id="' . $row['id'] . '" data-target="translate_packet" href="javascript:;">' . StaticFunctions::lang('446_translate') . '</a>
                                </li>
                                <li>
                                    <a class="js-modal-toggle" onclick="EditData(this);" data-edit="packets-' . $row['id'] . '" data-target="first-modal2" href="javascript:;">' . StaticFunctions::lang('120_edit') . '</a>
                                </li>
                                <li>
                                    <a onclick="DeleteData(\'Plan\',' . $row['id'] . ');" href="javascript:;">' . StaticFunctions::lang('121_delete') . '</a>
                                </li>
                            </ul>
                        </div>'

        ]);
    }
}