<?php

$PageCss = [
    '/assets/console/css/select2.min.css'
];
$PageJs = [
    '/assets/console/js/select2.min.js',
    '/assets/console/js/video.js',
    '/assets/console/js/table.movies.js',
    '/assets/console/js/movies.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');


?>
<style>
    .select2 {
        margin-top: 10px;
    }
</style>
<div class="content">

    <div class="row">
        <div>
            <h1><?= StaticFunctions::lang('108_movies') ?></h1>
        </div>
        <div>
            <a href="javascript:;" class="button js-modal-toggle" data-target="first-modal"><?= StaticFunctions::lang('144_add') ?></a>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="movies" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('227_movie') ?></th>
                    <th><?= StaticFunctions::lang('228_movie') ?></th>
                    <th><?= StaticFunctions::lang('229_movie') ?></th>
                    <th><?= StaticFunctions::lang('230_added') ?></th>
                    <th><?= StaticFunctions::lang('231_constraint') ?></th>
                    <th><?= StaticFunctions::lang('120_edit') ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>


<div class="modal js-modal" data-name="first-modal">
    <div class="backdrop"></div>
    <div style="margin-top: 0px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('232_add-new') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/new/movie" action="javascript:;" action="" method="post">

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('233_movie') ?>
                        <input required name="movie_name" placeholder="<?= StaticFunctions::lang('234_the-name-of-the') ?>" type="text" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('235_movie') ?>
                        <textarea required name="movie_description" style="min-height: 50px;" placeholder="<?= StaticFunctions::lang('236_description-of-the') ?>"></textarea>
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('237_movie-introduction') ?>
                        <input required name="movie_short_mp4" placeholder="<?= StaticFunctions::lang('238_introductory-file-of-the-movie') ?>" type="text" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('239_movie-video') ?>
                        <input required name="movie_mp4" placeholder="<?= StaticFunctions::lang('240_the-video-file-of-the-movie-m3u8') ?>" type="text" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('241_film') ?>
                        <input required name="movie_images[]" accept="image/*" multiple type="file" />
                    </label>
                </div>


                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('228_movie') ?>
                        <select required placeholder="<?= StaticFunctions::lang('242_select-the-categories-of-the') ?>" multiple id="NewMovieCategories" name="movie_categories[]">
                            <?php
                            $Genres = $db->query("SELECT * FROM genres ", PDO::FETCH_ASSOC);
                            if ($Genres->rowCount()) {
                                foreach ($Genres as $row) {
                                    echo '<option value="' . $row['id'] . '" >' . StaticFunctions::say($row['genres_name']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>


                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('243_monitoring') ?>
                        <select name="s18">
                            <option value="0">18+</option>
                            <option value="1">16+</option>
                            <option value="2">13+</option>
                            <option value="3">7+</option>
                            <option selected value="4"><?= Staticfunctions::lang('528_general') ?></option>
                            <option value="5"><?= Staticfunctions::lang('529_children-s') ?></option>
                        </select>
                    </label>
                </div>
            </div>


            <button class="button form_button"><?= StaticFunctions::lang('150_add') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>

<div class="modal js-modal" data-name="manage_actors">
    <div class="backdrop"></div>
    <div style="margin-top: 180px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('116_players') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/add/actors" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('233_movie') ?>
                        <input id="ActorsInput" disabled readonly type="text" />
                    </label>
                </div>

                <input type="text" hidden id="ActorsID" name="video_id" />
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('246_movie') ?>
                        <select placeholder="<?= StaticFunctions::lang('247_choose-the-actors-of-the') ?>" multiple id="ActorsSelect2" name="actors_actors[]">

                        </select>
                    </label>
                </div>



            </div>
            <button class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>

<div class="modal js-modal" data-name="manage_stream">
    <div class="backdrop"></div>
    <div style="margin-top: 150px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('248_video') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <?php
        echo '<div style="    margin-top: 20px;line-height: 22px;text-align:center;
    margin-bottom: 0px;" class="alert alert-info" role="alert">
        ' . StaticFunctions::lang('249_video-streams-are-created-with-ffmpeg') . '
    </div>';
        ?>
        <form onsubmit="SubmitForm(this);" data-source="web-service/create/stream" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('250_current-video') ?>
                        <input id="VideoLink" readonly type="text" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('251_current-broadcast') ?>
                        <input id="StreamLink" readonly type="text" />
                    </label>
                </div>

                <input type="text" hidden id="VideoIDStream" name="video_id" />



            </div>
            <button class="button form_button"><?= StaticFunctions::lang('252_publication') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>

<div class="modal js-modal" data-name="manage_directors">
    <div class="backdrop"></div>
    <div style="margin-top: 180px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('101_directors') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/add/directors" action="javascript:;" action="" method="post">
            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('233_movie') ?>
                        <input id="DirectorsInput" disabled readonly type="text" />
                    </label>
                </div>

                <input type="text" hidden id="DirectorsID" name="video_id" />
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('253_movie') ?>
                        <select placeholder="<?= StaticFunctions::lang('254_choose-the-directors-of-the') ?>" multiple id="DirectorsSelect2" name="directors_directors[]">

                        </select>
                    </label>
                </div>



            </div>
            <button class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>


<div class="modal js-modal" data-name="translate_video">
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
                <form onsubmit="SubmitForm(this);" data-source="web-service/translate/video" action="javascript:;" action="" method="post">

                    <div class="fields">
                        <div class="field">
                            <label>
                                <?= StaticFunctions::lang('233_movie') ?>
                                <input disabled name="translate_original_title" placeholder="<?= StaticFunctions::lang('234_the-name-of-the') ?>" type="text" />
                            </label>
                        </div>

                        <div class="field">
                            <label>
                                <?= StaticFunctions::lang('235_movie') ?>
                                <textarea disabled name="translate_original_description" style="min-height: 250px;" placeholder="<?= StaticFunctions::lang('236_description-of-the') ?>"></textarea>
                            </label>
                        </div>
                    </div>

                    <input hidden style="display: none;" type="text" name="translate_video_id" value="" />

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
                
                <form onsubmit="SubmitForm(this);" data-source="web-service/translate/video?' . $LangCode . '" action="javascript:;" method="post">

                    <div class="fields">
                        <div class="field">
                            <label>
                                ' . StaticFunctions::lang('233_movie') . '
                                <input data-translate-title="' . $Cd . '" name="translate_title" placeholder="' . StaticFunctions::lang('234_the-name-of-the') . '" type="text" />
                            </label>
                        </div>

                        <div class="field">
                            <label>
                                ' . StaticFunctions::lang('235_movie') . '
                                <textarea data-translate-text="' . $Cd . '" name="translate_description" style="min-height: 250px;" placeholder="' . StaticFunctions::lang('236_description-of-the') . '"></textarea>
                            </label>
                        </div>
                    </div>

                    <input hidden style="display: none;" type="text" name="translate_lang_code" value="' . $LangCode . '" />
                    <input hidden style="display: none;" type="text" name="translate_video_id" value="" />

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
    <div style="margin-top: 0px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('255_edit') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitForm(this);" data-source="web-service/edit/movie" action="javascript:;" action="" method="post">

            <div class="fields">
                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('233_movie') ?>
                        <input required name="edit_video_name" placeholder="<?= StaticFunctions::lang('234_the-name-of-the') ?>" type="text" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('235_movie') ?>
                        <textarea required name="edit_video_description" style="min-height: 50px;" placeholder="<?= StaticFunctions::lang('236_description-of-the') ?>"></textarea>
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('237_movie-introduction') ?>
                        <input required name="edit_video_short_source" placeholder="<?= StaticFunctions::lang('238_introductory-file-of-the-movie') ?>" type="text" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('239_movie-video') ?>
                        <input required name="edit_video_source" placeholder="<?= StaticFunctions::lang('240_the-video-file-of-the-movie-m3u8') ?>" type="text" />
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('241_film') ?>
                        <input name="images_inp[]" accept="image/*" multiple type="file" />
                    </label>
                </div>

                <div style="margin-top: 10px;" id="EditImages">

                </div>


                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('228_movie') ?>
                        <select required placeholder="<?= StaticFunctions::lang('242_select-the-categories-of-the') ?>" multiple id="EditMovieCategories" name="edit_video_categories[]">
                            <?php
                            $Genres = $db->query("SELECT * FROM genres ", PDO::FETCH_ASSOC);
                            if ($Genres->rowCount()) {
                                foreach ($Genres as $row) {
                                    echo '<option value="' . $row['id'] . '" >' . StaticFunctions::say($row['genres_name']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>

                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('243_monitoring') ?>
                        <select name="edit_video_level">
                            <option value="0">18+</option>
                            <option value="1">16+</option>
                            <option value="2">13+</option>
                            <option value="3">7+</option>
                            <option value="4"><?= Staticfunctions::lang('528_general') ?></option>
                            <option value="5"><?= Staticfunctions::lang('529_children-s') ?></option>
                        </select>
                    </label>
                </div>
            </div>

            <input hidden style="display: none;" type="text" name="edit_id" value="" />


            <button class="button form_button"><?= StaticFunctions::lang('120_edit') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>
<input id="PlaceHolderSlt" hidden value="<?= StaticFunctions::lang('256_select') ?>" />


<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
