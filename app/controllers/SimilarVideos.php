<?php


StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$Token = StaticFunctions::post('itemID');
$VideoID = explode('87654', $Token, 2)[1];

$LastVideo = $db->query("SELECT * from series_and_movies  WHERE id='{$VideoID}' ")->fetch(PDO::FETCH_ASSOC);

if (!$LastVideo) {
    http_response_code(401);
    exit;
}

$ProfileID = StaticFunctions::GetProfileId();
$ProfileLevel = $db->query("SELECT profile_level from profiles WHERE id='{$ProfileID}' ")->fetch(PDO::FETCH_ASSOC)['profile_level'];
$CheckUserData = StaticFunctions::MyDataQuery();
$MyList = json_decode($CheckUserData['my_list'], true);

$VideoCategories = json_decode($LastVideo['video_categories'], true);
$SqlQuery = "SELECT * from series_and_movies WHERE id != '{$LastVideo['id']}' and '{$ProfileLevel}' <= video_level  and ( video_type='movie' or video_type='series' ) and ( video_categories LIKE '%\"0\"%' ";
foreach ($VideoCategories as $key => $category) {
    $SqlQuery .= " or video_categories LIKE '%\"" . $category . "\"%' ";
}
$SqlQuery .= ") order by Rand() LIMIT 6 ";
$SimilarItems = $db->query($SqlQuery, PDO::FETCH_ASSOC);
if ($SimilarItems->rowCount()) {
?>
<div class="similars">
    <div class="head"><?= Staticfunctions::lang('546_similar') ?></div>
    <div class="items">
        <?php

            foreach ($SimilarItems as $key => $row) {

                $Item = $row;
                if ($row['video_type'] == 'series' || $row['video_type'] == 'season') {
                    $SeriesID = ($row['video_type'] == 'series') ? $row['id'] : $row['series_id'];
                    $row = $db->query("SELECT * from series_and_movies WHERE series_id='{$SeriesID}' and video_type='episode' order by id ASC ")->fetch(PDO::FETCH_ASSOC);
                }

                $VideoImg = json_decode($row['video_images'], true)[0];
                echo '<div class="item">
                            <a href="' . PATH . 'browse/87654' . $Item['id'] . '">
                        <div class="image">
                            <div class="play">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 494.148 494.148"
                                    style="enable-background:new 0 0 494.148 494.148;" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path fill="#fff"
                                                d="M405.284,201.188L130.804,13.28C118.128,4.596,105.356,0,94.74,0C74.216,0,61.52,16.472,61.52,44.044v406.124    c0,27.54,12.68,43.98,33.156,43.98c10.632,0,23.2-4.6,35.904-13.308l274.608-187.904c17.66-12.104,27.44-28.392,27.44-45.884    C432.632,229.572,422.964,213.288,405.284,201.188z" />
                                        </g>
                                    </g>
                                </svg>
                            </div>';

                if ($row['video_type'] != 'movie') {
                    $SeriesID = ($row['video_type'] == 'series') ? $row['id'] : $row['series_id'];
                    $SeasonCount = $db->query("SELECT id from series_and_movies WHERE series_id='{$SeriesID}' and video_type='season' ", PDO::FETCH_ASSOC);
                    $RowCount = $SeasonCount->rowCount();
                    if ($RowCount && $RowCount > 0) {
                        echo '<div class="season">' . $RowCount . ' ' . Staticfunctions::lang('64_season') . ' </div>';
                    }
                }

                $InList = false;
                foreach ($MyList as $key => $Id) {
                    if ($Id == $Item['id']) {
                        $InList = true;
                        break;
                    }
                }

                if ($InList == true) {
                    $DisplayList2 = '';
                    $DisplayList1 = ' style="display:none;" ';
                } else {
                    $DisplayList1 = '';
                    $DisplayList2 = ' style="display:none;" ';
                }

                echo '<img src="' . $VideoImg . '"
                                alt="" />
                            <div class="play-icon"></div>
                        </div>
                        </a>
                        <span class="text">
                            <div class="badges">
                                <div class="button-container">
                                    <span ' . $DisplayList1 . ' class="button"  data-token=" ' . $row['video_token'] . '" onClick="ListAdded1(this);"
                                        data-tooltip="' . Staticfunctions::lang('531_add-to-my') . '" >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1"
                                            x="0px" y="0px" viewBox="0 0 492 492"
                                            style="enable-background:new 0 0 492 492;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M465.064,207.566l0.028,0H284.436V27.25c0-14.84-12.016-27.248-26.856-27.248h-23.116    c-14.836,0-26.904,12.408-26.904,27.248v180.316H26.908c-14.832,0-26.908,12-26.908,26.844v23.248    c0,14.832,12.072,26.78,26.908,26.78h180.656v180.968c0,14.832,12.064,26.592,26.904,26.592h23.116    c14.84,0,26.856-11.764,26.856-26.592V284.438h180.624c14.84,0,26.936-11.952,26.936-26.78V234.41    C492,219.566,479.904,207.566,465.064,207.566z" />
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <span ' . $DisplayList2 . ' class="button"  data-token=" ' . $row['video_token'] . '" onClick="ListAdded2(this);"
                                        data-tooltip="' . Staticfunctions::lang('532_remove-from-my') . '" >
                                        <svg style="transform: rotate(135deg)" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1"
                                            x="0px" y="0px" viewBox="0 0 492 492"
                                            style="enable-background:new 0 0 492 492;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M465.064,207.566l0.028,0H284.436V27.25c0-14.84-12.016-27.248-26.856-27.248h-23.116    c-14.836,0-26.904,12.408-26.904,27.248v180.316H26.908c-14.832,0-26.908,12-26.908,26.844v23.248    c0,14.832,12.072,26.78,26.908,26.78h180.656v180.968c0,14.832,12.064,26.592,26.904,26.592h23.116    c14.84,0,26.856-11.764,26.856-26.592V284.438h180.624c14.84,0,26.936-11.952,26.936-26.78V234.41    C492,219.566,479.904,207.566,465.064,207.566z" />
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <span class="svideoName">' . StaticFunctions::VideoTranslation($Item['video_name'], $Item['video_translations'], 'name') . '</span>';

                $VideoLevel = StaticFunctions::VideoAdulthoodLevel($row['video_level']);
                if ($VideoLevel != '__noLevel__') {
                    echo ' <span class="age">' . $VideoLevel . '</span>';
                }

                echo ' <span class="year">' . $row['video_year'] . '</span>
                                <span class="add-list">
                                </span>
                            </div>
                            <p>
                                ' . StaticFunctions::TrimText2(StaticFunctions::VideoTranslation($row['video_description'], $row['video_translations'], 'description'), 120) . '
                            </p>
                        </span>
                    </div>';
            }

            ?>

    </div>
</div>
<?php } ?>