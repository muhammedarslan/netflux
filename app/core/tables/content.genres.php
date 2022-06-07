<?php

//sleep(1);
$DataArray = [];

$Me = StaticFunctions::get_id();

$GetData = $db->query("SELECT * FROM genres order by id DESC", PDO::FETCH_ASSOC);
if ($GetData->rowCount()) {
    foreach ($GetData as $row) {
        array_push($DataArray, [
            ' <span class="Location">' . $row['id'] . '</span>',
            '<span class="name">' . StaticFunctions::say($row['genres_name']) . '</span>',
            ' <span class="Location"><a target="_blank" href="' . PATH . 'browse/genre/87654' . $row['id'] . '">' . StaticFunctions::lang('124_click-to-go-to-the-genre') . '</a> </span>',
            '<div onclick="OpenDropdownR(this); return false;" class="dropdown js2-dropdown">
                            <div class="toggle">...</div>
                            <ul>
                            <li>
                                    <a class="js-modal-toggle" onclick="TranslateGenre(this);" data-id="' . $row['id'] . '" data-target="translate_genre" href="javascript:;">' . StaticFunctions::lang('446_translate') . '</a>
                                </li>
                                <li>
                                    <a class="js-modal-toggle" onclick="EditData(this);" data-edit="genres-' . $row['id'] . '" data-target="first-modal2" href="javascript:;">' . StaticFunctions::lang('120_edit') . '</a>
                                </li>
                                <li>
                                    <a onclick="DeleteData(\'Genres\',' . $row['id'] . ');" href="javascript:;">' . StaticFunctions::lang('121_delete') . '</a>
                                </li>
                            </ul>
                        </div>'

        ]);
    }
}