<?php

$DataArray2 = [];

$Me = StaticFunctions::get_id();
$N = 0;
$Limit = StaticFunctions::post('start') . ',' . StaticFunctions::post('length');

$SearchData = StaticFunctions::post('search')['value'];
$Order = StaticFunctions::post('order')[0]['dir'];



if ($SearchData != '') {
    $TotalRowCount = $db->query("SELECT id FROM series_and_movies WHERE video_type='movie' and video_name LIKE '%{$SearchData}%' ", PDO::FETCH_ASSOC);
    $GetData = $db->query("SELECT * FROM series_and_movies WHERE video_type='movie' and video_name LIKE '%{$SearchData}%' order by id $Order LIMIT $Limit ", PDO::FETCH_ASSOC);
} else {
    $TotalRowCount = $db->query("SELECT id FROM series_and_movies WHERE video_type='movie' ", PDO::FETCH_ASSOC);
    $GetData = $db->query("SELECT * FROM series_and_movies WHERE video_type='movie' order by id $Order LIMIT $Limit ", PDO::FETCH_ASSOC);
}

$TotalRow = $TotalRowCount->rowCount();

if ($GetData->rowCount()) {
    foreach ($GetData as $row) {

        $Cat = [];
        foreach (json_decode($row['video_categories']) as $key => $cat) {
            $GetCat = $db->query("SELECT * FROM genres WHERE id = '{$cat}'")->fetch(PDO::FETCH_ASSOC);
            if ($GetCat) {
                array_push($Cat, StaticFunctions::lang($GetCat['genres_name']));
            }
        }

        $Cat = implode(', ', $Cat);

        $Kst = StaticFunctions::lang('126_not');
        $VideoLevel = StaticFunctions::VideoAdulthoodLevel($row['video_level']);
        if ($VideoLevel != '__noLevel__') {
            $Kst = $VideoLevel;
        }

        array_push($DataArray2, [
            ' <span class="Location">' . $row['id'] . '</span>',
            '<a target="_blank" href="' . $row['video_main_image'] . '"><img style="    height: 40px;
    width: 60px;
            top:25%;
    margin-left: -10px;" src="' . $row['video_main_image'] . '" alt=""></a>
                    <span class="name">' . StaticFunctions::say($row['video_name']) . '</span>',
            ' <span class="Location">' . $Cat . '</span>',
            '<span class="Location"><a target="_blank" href="' . PATH . 'browse/87654' . $row['id'] . '">' . StaticFunctions::lang('128_click') . '</a> </span>',
            '<span style="font-weight:600;" class="Location">' . StaticFunctions::timerFormat($row['created_time'], time()) . ' ' . StaticFunctions::lang('129_before') . '</span><br>
                    <span style="margin-top:7px;display:block;" class="Location">' . date('d-m-Y H:i:s', $row['created_time']) . '</span>',
            ' <span class="Location">' . $Kst . '</span>',
            '<div onclick="OpenDropdownR(this); return false;" class="dropdown js2-dropdown">
                            <div class="toggle">...</div>
                            <ul>
                             <li>
                                    <a class="js-modal-toggle" onclick="CreateStream(this);" data-id="' . $row['id'] . '" data-target="manage_stream" href="javascript:;">' . StaticFunctions::lang('130_create') . '</a>
                                </li>
                             <li>
                                    <a class="js-modal-toggle" onclick="TranslateVideo(this);" data-id="' . $row['id'] . '" data-target="translate_video" href="javascript:;">' . StaticFunctions::lang('446_translate') . '</a>
                                </li>
                             <li>
                                    <a class="js-modal-toggle" onclick="ManageActors(this);" data-id="' . $row['id'] . '" data-target="manage_actors" href="javascript:;">' . StaticFunctions::lang('116_players') . '</a>
                                </li>
                             <li>
                                    <a class="js-modal-toggle" onclick="ManageDirectors(this);" data-id="' . $row['id'] . '" data-target="manage_directors" href="javascript:;">' . StaticFunctions::lang('101_directors') . '</a>
                                </li>
                                <li>
                                    <a class="js-modal-toggle" onclick="EditData(this);EditMovie(this);" data-edit="series_and_movies-' . $row['id'] . '" data-target="first-modal2" href="javascript:;">' . StaticFunctions::lang('120_edit') . '</a>
                                </li>
                                <li>
                                    <a onclick="DeleteData(\'Movie\',' . $row['id'] . ');" href="javascript:;">' . StaticFunctions::lang('121_delete') . '</a>
                                </li>
                            </ul>
                        </div>'

        ]);
    }
}

$DataArray = [
    'draw' => StaticFunctions::post('draw'),
    'recordsTotal' => $TotalRow,
    'recordsFiltered' => $TotalRow,
    'data' => $DataArray2
];

echo StaticFunctions::ApiJson($DataArray);
exit;