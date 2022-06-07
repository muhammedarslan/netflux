<?php

StaticFunctions::NoBarba();

$PageCss = [];
$PageJs  = [
    '/assets/netflux/js/profile.js'
];

require_once StaticFunctions::View('V' . '/profile.header.php');

?>
<!DOCTYPE html>
<html lang="<?= LANG ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= StaticFunctions::lang('396_profile') . ' ' . PR_NAME ?></title>
    <link rel="icon" href="<?= PATH ?>assets/media/fav.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/reset.min.css">
    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/slick.min.css">
    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/main.css">
    <script>
    var InternalAjaxHost = '<?= PROTOCOL . DOMAIN . PATH ?>';
    </script>
</head>
<style>
::-webkit-scrollbar {
    display: none;
}
</style>

<body data-barba="wrapper">
    <div class="PureBlack" style="height:1000px;display:block;"></div>

    <main style="display: none;" class="MainContent" data-barba="container" data-barba-easy="ProfileSelect<?= LANG ?>">
        <!-- partial:index.partial.html -->
        <div class="logo manage-profile" style="padding: 13px 56px">
            <a href="/browse" class="logo-nav">
                <img src="/assets/netflux/images/logo.png" alt="Netflux Logo">
            </a>
        </div>


        <div class="apply-photo-modal">
            <div class="content">
                <div class="hero">
                    <?= Staticfunctions::lang('489_change-profile') ?>
                </div>
                <div class="images">
                    <div>
                        <img id="apply-photo-img1" src="/assets/media/box_grey.png" alt="" />
                        <div class="name"><?= Staticfunctions::lang('490_current') ?></div>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                            id="Layer_1" x="0px" y="0px" viewBox="0 0 492.004 492.004"
                            style="enable-background:new 0 0 492.004 492.004;" xml:space="preserve">
                            <g>
                                <g>
                                    <path fill="#fff"
                                        d="M382.678,226.804L163.73,7.86C158.666,2.792,151.906,0,144.698,0s-13.968,2.792-19.032,7.86l-16.124,16.12    c-10.492,10.504-10.492,27.576,0,38.064L293.398,245.9l-184.06,184.06c-5.064,5.068-7.86,11.824-7.86,19.028    c0,7.212,2.796,13.968,7.86,19.04l16.124,16.116c5.068,5.068,11.824,7.86,19.032,7.86s13.968-2.792,19.032-7.86L382.678,265    c5.076-5.084,7.864-11.872,7.848-19.088C390.542,238.668,387.754,231.884,382.678,226.804z" />
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <img id="apply-photo-img2" src="/assets/media/box_grey.png" alt="" />
                        <div class="name"><?= Staticfunctions::lang('491_new') ?></div>
                    </div>
                </div>
                <div class="buttons">
                    <a href="javascript:;" class="apply"><?= Staticfunctions::lang('492_let-s-do') ?></a>
                    <a href="javascript:;"><?= Staticfunctions::lang('493_not') ?></a>
                </div>
            </div>
        </div>

        <div class="delete-profile-modal">
            <div class="content">
                <div class="hero">
                    <?= Staticfunctions::lang('495_delete-this') ?>
                </div>
                <div style="width: 100%;text-align:center;" class="row">
                    <span id="deleteProfileName" style="border-bottom:none;" class="hero"></span>
                </div>
                <div class="buttons">
                    <a href="javascript:;" class="apply"><?= Staticfunctions::lang('494_delete') ?></a>
                    <a href="javascript:;"><?= Staticfunctions::lang('493_not') ?></a>
                </div>
            </div>
        </div>

        <div class="photos-modal">
            <div class="content">
                <div class="hero-container">
                    <div class="hero">
                        <div class="back">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492"
                                style="enable-background:new 0 0 492 492;" xml:space="preserve">
                                <g>
                                    <g>
                                        <path fill="#fff"
                                            d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <?= Staticfunctions::lang('496_edit') ?>
                        <p><?= Staticfunctions::lang('497_choose-a-profile') ?></p>
                        <div id="select-avatar-user" class="user">
                            <span id="editRealName"></span>
                            <img src="/assets/media/box_grey.png" id="imagesTopAvatar" alt="" />
                        </div>
                    </div>
                </div>
                <div class="photos">
                    <?php

                    $AvatarsArray = [];
                    $Avatars = $db->query("SELECT * from avatars", PDO::FETCH_ASSOC);
                    if ($Avatars->rowCount()) {
                        foreach ($Avatars as $key => $avatar) {
                            $AvatarsArray[$avatar['avatar_group']][$avatar['id']] = $avatar['avatar_path'];
                        }
                    }

                    foreach ($AvatarsArray as $key => $list) {

                        $RowName = ($key == 'Klasikler') ? StaticFunctions::lang('518_classics') : StaticFunctions::say($key);

                        echo '<div class="photos-row">
                        <div class="label">' . $RowName . '</div>
                        <div class="js-photos-carousel">';

                        foreach ($list as $key => $item) {
                            echo '<div>
                                <a href="javascript:;" class="item">
                                    <img class="lazyload" width="200px" data-src="' . PATH . 'assets' . $item . '" height="200px" src="/assets/media/box_grey.png"
                                        alt="" />
                                </a>
                            </div>';
                        }

                        echo '</div>
                    </div>';
                    }

                    ?>

                </div>
            </div>
        </div>

        <div class="user-select">
            <div class="hero js-hero-text"><?= Staticfunctions::lang('498_who-is') ?></div>
            <div class="users">
                <?php
                foreach ($Profiles as $key => $Profile) {
                    echo '
                        <div>
                            <span class="item" onclick="ProfileSelect(' . "'" . $Profile['profile_token'] . "'" . ');">
                                <img src="' . $Profile['profile_avatar'] . '" alt="" />
                                <div class="name">' . StaticFunctions::say($Profile['profile_name']) . '</div>
                                <div class="edit">
                                    <svg id="edit" viewBox="0 0 32 32">    <path fill="currentColor" d="M16 0c8.833 0 16 7.167 16 16 0 8.8-7.167 16-16 16s-16-7.2-16-16c0-8.833 7.167-16 16-16zM16 1.7c-7.9 0-14.3 6.4-14.3 14.3s6.4 14.3 14.3 14.3 14.3-6.4 14.3-14.3-6.4-14.3-14.3-14.3zM22.333 12.9l0.3-0.267 0.867-0.867c0.467-0.5 0.4-0.767 0-1.167l-1.767-1.767c-0.467-0.467-0.767-0.4-1.167 0l-0.867 0.867-0.267 0.3zM18.3 11.1l-8.6 8.6-0.833 3.767 3.767-0.833 0.967-1 7.633-7.6z"></path></svg>
                                </div>                                
                            </span>
                        </div>                        
                        ';
                }

                if ($AllowNew) {
                    echo '
                        <div>
                            <span class="item" onclick="NewProfile();">
                                <div class="new-icon-area">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="16" height="16" viewBox="0 0 16 16">
                                    <path fill="#444444" d="M8 0c-4.4 0-8 3.6-8 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zM13 9h-4v4h-2v-4h-4v-2h4v-4h2v4h4v2z"/>
                                    </svg>
                                </div>
                                <div class="name">' . StaticFunctions::lang('398_add-new') . '</div>
                            </span>
                        </div>';
                }
                ?>
            </div>
            <div class="button">
                <a onclick="ManageProfiles();" href="javascript:;"
                    class="edit-button"><?= Staticfunctions::lang('499_manage') ?></a>
                <a onclick="ManageProfiles();" href="javascript:;"
                    class="apply"><?= Staticfunctions::lang('500_ok') ?></a>
            </div>
        </div>

        <div style="display: none;" class="add-profile-modal">


            <div class="AddProfileModalG">

                <div onclick="NewProfileImage();" class="profile click_pr add_profile_div1">
                    <div class="profile-icon profile4">
                    </div>
                </div>

                <div class="input-group form-login form-field add_profile_div2">
                    <input autocomplete="off" name="new_profile_name" required="" type="text"
                        class="form-control form-input new_profile_name">
                    <label class="NewProfileLabel" for="new_profile_name"><?= StaticFunctions::lang('400_give-this-profile-a') ?></label>
                    <form action="javascript:;" style="display: none;" class="ImageForm">
                        <input autocomplete="off" hidden accept="image/*" name="new_profile_avatar" type="file"
                            class="form-control form-input new_profile_avatar">
                        <input type="text" value="text" hidden name="text" />
                    </form>

                </div>

            </div>

        </div> <!-- partial -->

        <div class="edit-modal">
            <div class="content">
                <div class="hero">
                    <?= Staticfunctions::lang('496_edit') ?>
                </div>
                <div class="field-area">
                    <div class="avatar-area">
                        <img id="editProfileStaticAvatar" src="/assets/media/box_grey.png" alt=""
                            style="width: 134px" />
                        <!--<form action="javascript:;" style="display: none;" class="ImageFormEdit">
                            <input autocomplete="off" hidden accept="image/*" name="edit_profile_avatar" type="file" class="form-control form-input edit_profile_avatar">
                            <input type="text" value="text" hidden name="text" />
                            <input type="text" value="" class="edit_avatar_token" hidden name="token" />
                        </form>             -->
                        <div class="select-photo">
                            <!--<input type="file" />-->
                            <svg id="edit" viewBox="0 0 32 32">
                                <path fill="#fff"
                                    d="M16 0c8.833 0 16 7.167 16 16 0 8.8-7.167 16-16 16s-16-7.2-16-16c0-8.833 7.167-16 16-16zM16 1.7c-7.9 0-14.3 6.4-14.3 14.3s6.4 14.3 14.3 14.3 14.3-6.4 14.3-14.3-6.4-14.3-14.3-14.3zM22.333 12.9l0.3-0.267 0.867-0.867c0.467-0.5 0.4-0.767 0-1.167l-1.767-1.767c-0.467-0.467-0.767-0.4-1.167 0l-0.867 0.867-0.267 0.3zM18.3 11.1l-8.6 8.6-0.833 3.767 3.767-0.833 0.967-1 7.633-7.6z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="fields">
                        <div class="field">
                            <input maxlength="60" autocomplete="off" name="edit_profile_name" required="" type="text"
                                class="form-control form-input edit_profile_name">
                        </div>
                        <div class="field">
                            <label><?= Staticfunctions::lang('501_tongue') ?></label>
                            <select id="edit-profile-language">
                                <?php
                                $Languages = AppLanguage::GetAllowedLangs();
                                foreach ($Languages as $key => $value) {
                                    echo '<option value="' . $key . '" >' . $value['LangName'] . '</option>' . "\n";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="field field-row">
                            <div class="subhead"><?= Staticfunctions::lang('502_adult') ?></div>


                            <select id="edit-profile-level-select" style="width: 240px;margin-bottom:10px;">
                                <option value="0"><?= Staticfunctions::lang('503_all-adult') ?></option>
                                <option value="1"><?= Staticfunctions::lang('504_16-contents') ?></option>
                                <option value="2"><?= Staticfunctions::lang('505_only-13') ?></option>
                                <option value="3"><?= Staticfunctions::lang('506_only-7') ?></option>
                                <option value="4"><?= Staticfunctions::lang('507_general-audience') ?></option>
                            </select>

                            <select disabled id="edit-profile-child-select"
                                style="width: 263px;margin-bottom:10px;display:none;">
                                <option selected value="5"><?= Staticfunctions::lang('508_contents-for') ?></option>
                            </select>

                            <ul>
                                <li>
                                    <input onclick="EditChildCheckbox();" id="childCheckboxE" type="checkbox" />
                                    <div class="check">
                                        <svg id="new-profile-check-mark" viewBox="0 0 32 32">
                                            <path fill="currentColor"
                                                d="M14.133 23.5l12.767-12.467c0.033-0.033 0.1-0.1 0.133-0.167l-3.1-3.133c-0.067 0.033-0.133 0.1-0.2 0.167l-11.267 10.933-4.267-4.333-3.233 2.933c0.033 0 1.067 1.067 1.1 1.067l4.767 5 0.133 0.133c0.433 0.4 0.967 0.633 1.5 0.633s1.1-0.233 1.5-0.633z">
                                            </path>
                                        </svg>
                                    </div>
                                    <?= Staticfunctions::lang('509_show-only-content-for-kids-on-this') ?>
                                </li>
                            </ul>


                        </div>
                        <div class="field-row">
                            <div class="subhead"><?= Staticfunctions::lang('510_auto-play') ?></div>
                            <ul>
                                <li>
                                    <input id="edit-playback-contoller1" type="checkbox" />
                                    <div class="check">
                                        <svg id="check-mark" viewBox="0 0 32 32">
                                            <path fill="currentColor"
                                                d="M14.133 23.5l12.767-12.467c0.033-0.033 0.1-0.1 0.133-0.167l-3.1-3.133c-0.067 0.033-0.133 0.1-0.2 0.167l-11.267 10.933-4.267-4.333-3.233 2.933c0.033 0 1.067 1.067 1.1 1.067l4.767 5 0.133 0.133c0.433 0.4 0.967 0.633 1.5 0.633s1.1-0.233 1.5-0.633z">
                                            </path>
                                        </svg>
                                    </div>
                                    <?= Staticfunctions::lang('511_automatically-play-the-next-episode-on') ?>
                                </li>
                                <li>
                                    <input id="edit-playback-contoller2" type="checkbox" />
                                    <div class="check">
                                        <svg id="check-mark" viewBox="0 0 32 32">
                                            <path fill="currentColor"
                                                d="M14.133 23.5l12.767-12.467c0.033-0.033 0.1-0.1 0.133-0.167l-3.1-3.133c-0.067 0.033-0.133 0.1-0.2 0.167l-11.267 10.933-4.267-4.333-3.233 2.933c0.033 0 1.067 1.067 1.1 1.067l4.767 5 0.133 0.133c0.433 0.4 0.967 0.633 1.5 0.633s1.1-0.233 1.5-0.633z">
                                            </path>
                                        </svg>
                                    </div>
                                    <?= Staticfunctions::lang('512_automatically-play-previews-on-all') ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button class="submit"><?= Staticfunctions::lang('513_save') ?></button>
                    <button class="cancel"><?= Staticfunctions::lang('514_cancel') ?></button>
                    <button onclick="DeleteProfile();" class=""><?= Staticfunctions::lang('515_delete') ?></button>
                </div>
            </div>
        </div>

        <div class="new-profile-modal">
            <div class="content">
                <div class="hero">
                    <?= Staticfunctions::lang('516_add') ?>
                </div>
                <div class="field-area">
                    <div class="avatar-area">
                        <img id="new_profile_default_avatar" src="<?= PATH ?>assets/media/default_avatar.png" alt=""
                            style="width: 134px" />
                        <!--<form action="javascript:;" style="display: none;" class="ImageFormEdit">
                            <input autocomplete="off" hidden accept="image/*" name="edit_profile_avatar" type="file" class="form-control form-input edit_profile_avatar">
                            <input type="text" value="text" hidden name="text" />
                            <input type="text" value="" class="edit_avatar_token" hidden name="token" />
                        </form>             -->
                        <div class="select-photo">
                            <!--<input type="file" />-->
                            <svg id="new-profile-edit" viewBox="0 0 32 32">
                                <path fill="#fff"
                                    d="M16 0c8.833 0 16 7.167 16 16 0 8.8-7.167 16-16 16s-16-7.2-16-16c0-8.833 7.167-16 16-16zM16 1.7c-7.9 0-14.3 6.4-14.3 14.3s6.4 14.3 14.3 14.3 14.3-6.4 14.3-14.3-6.4-14.3-14.3-14.3zM22.333 12.9l0.3-0.267 0.867-0.867c0.467-0.5 0.4-0.767 0-1.167l-1.767-1.767c-0.467-0.467-0.767-0.4-1.167 0l-0.867 0.867-0.267 0.3zM18.3 11.1l-8.6 8.6-0.833 3.767 3.767-0.833 0.967-1 7.633-7.6z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="fields">
                        <div class="field">
                            <input autocomplete="off" maxlength="60" minlength="2"
                                placeholder="<?= StaticFunctions::lang('517_give-this-profile-a') ?>" name="edit_profile_name" required=""
                                type="text" class="form-control form-input edit_profile_name">
                        </div>
                        <div class="field">
                            <label><?= Staticfunctions::lang('501_tongue') ?></label>
                            <select id="new-profile-language">
                                <?php
                                $Languages = AppLanguage::GetAllowedLangs();
                                $SelectedLang = LANG;
                                echo '<option selected value="' . $SelectedLang . '" >' . $Languages[LANG]['LangName'] . '</option>' . "\n";
                                unset($Languages[LANG]);
                                foreach ($Languages as $key => $value) {
                                    echo '<option value="' . $key . '" >' . $value['LangName'] . '</option>' . "\n";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="field field-row">
                            <div class="subhead"><?= Staticfunctions::lang('502_adult') ?></div>


                            <select id="new-profile-level-select" style="width: 240px;margin-bottom:10px;">
                                <option value="0"><?= Staticfunctions::lang('503_all-adult') ?></option>
                                <option value="1"><?= Staticfunctions::lang('504_16-contents') ?></option>
                                <option value="2"><?= Staticfunctions::lang('505_only-13') ?></option>
                                <option value="3"><?= Staticfunctions::lang('506_only-7') ?></option>
                                <option value="4"><?= Staticfunctions::lang('507_general-audience') ?></option>
                            </select>

                            <select disabled id="new-profile-child-select"
                                style="width: 263px;margin-bottom:10px;display:none;">
                                <option selected value="5"><?= Staticfunctions::lang('508_contents-for') ?></option>
                            </select>

                            <ul>
                                <li>
                                    <input onclick="ChildCheckbox();" id="childCheckboxD" type="checkbox" />
                                    <div class="check">
                                        <svg id="new-profile-check-mark" viewBox="0 0 32 32">
                                            <path fill="currentColor"
                                                d="M14.133 23.5l12.767-12.467c0.033-0.033 0.1-0.1 0.133-0.167l-3.1-3.133c-0.067 0.033-0.133 0.1-0.2 0.167l-11.267 10.933-4.267-4.333-3.233 2.933c0.033 0 1.067 1.067 1.1 1.067l4.767 5 0.133 0.133c0.433 0.4 0.967 0.633 1.5 0.633s1.1-0.233 1.5-0.633z">
                                            </path>
                                        </svg>
                                    </div>
                                    <?= Staticfunctions::lang('509_show-only-content-for-kids-on-this') ?>
                                </li>
                            </ul>


                        </div>
                        <div class="field-row">
                            <div class="subhead"><?= Staticfunctions::lang('510_auto-play') ?></div>
                            <ul>
                                <li>
                                    <input id="new-account-playback-c1" checked type="checkbox" />
                                    <div class="check">
                                        <svg id="new-profile-check-mark" viewBox="0 0 32 32">
                                            <path fill="currentColor"
                                                d="M14.133 23.5l12.767-12.467c0.033-0.033 0.1-0.1 0.133-0.167l-3.1-3.133c-0.067 0.033-0.133 0.1-0.2 0.167l-11.267 10.933-4.267-4.333-3.233 2.933c0.033 0 1.067 1.067 1.1 1.067l4.767 5 0.133 0.133c0.433 0.4 0.967 0.633 1.5 0.633s1.1-0.233 1.5-0.633z">
                                            </path>
                                        </svg>
                                    </div>
                                    <?= Staticfunctions::lang('511_automatically-play-the-next-episode-on') ?>
                                </li>
                                <li>
                                    <input id="new-account-playback-c2" type="checkbox" />
                                    <div class="check">
                                        <svg id="new-profile-check-mark" viewBox="0 0 32 32">
                                            <path fill="currentColor"
                                                d="M14.133 23.5l12.767-12.467c0.033-0.033 0.1-0.1 0.133-0.167l-3.1-3.133c-0.067 0.033-0.133 0.1-0.2 0.167l-11.267 10.933-4.267-4.333-3.233 2.933c0.033 0 1.067 1.067 1.1 1.067l4.767 5 0.133 0.133c0.433 0.4 0.967 0.633 1.5 0.633s1.1-0.233 1.5-0.633z">
                                            </path>
                                        </svg>
                                    </div>
                                    <?= Staticfunctions::lang('512_automatically-play-previews-on-all') ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="buttons">
                    <button class="submit"><?= Staticfunctions::lang('513_save') ?></button>
                    <button class="cancel"><?= Staticfunctions::lang('514_cancel') ?></button>
                </div>
            </div>
        </div>

        <div style="display: none;" class="edit-profile-modal">


            <div class="EditProfileModalG">

                <div onclick="EditProfileImage();" class="profile click_pr edit_profile_div1">
                    <div class="profile-icon profile4">
                    </div>
                </div>

                <div class="input-group form-login form-field edit_profile_div2">
                    <input autocomplete="off" name="edit_profile_name" required="" type="text"
                        class="form-control form-input edit_profile_name">
                    <label class="EditProfileLabel"
                        for="edit_profile_name"><?= StaticFunctions::lang('401_edit-this-profile') ?></label>
                    <form action="javascript:;" style="display: none;" class="ImageFormEdit">
                        <input autocomplete="off" hidden accept="image/*" name="edit_profile_avatar" type="file"
                            class="form-control form-input edit_profile_avatar">
                        <input type="text" value="text" hidden name="text" />
                        <input type="text" value="" class="edit_avatar_token" hidden name="token" />
                    </form>

                </div>

            </div>

        </div> <!-- partial -->

        <input type="text" id="editCurrentAvatarSrc" value="" hidden>
        <input type="text" id="editedProfileToken" value="" hidden>
        <input type="text" id="Mode" value="normal" hidden />
        <input type="text" id="AddProf" value="<?= StaticFunctions::BoolText($AllowNew) ?>" hidden />
        <input type="text" id="NormalWidth" value="<?= $NormalWidth ?>" hidden />
        <input type="text" id="EditWidth" value="<?= $EditWidth ?>" hidden />
        <input type="text" id="CloseInp" value="<?= StaticFunctions::lang('25_close') ?>" hidden />
        <input type="text" id="AddInp" value="<?= StaticFunctions::lang('402_create') ?>" hidden />
        <input type="text" id="Title1" value="<?= StaticFunctions::lang('498_who-is') ?>" hidden />
        <input type="text" id="Title2" value="<?= StaticFunctions::lang('519_profile') ?>" hidden />
    </main>

    <script src="<?= PATH ?>assets/netflux/js/jquery-3.5.1.min.js" crossorigin="anonymous">
    </script>
    <script src="<?= PATH ?>assets/netflux/js/lazyload.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/topbar.min.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/bootstrap-validate.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/barba.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/slick.min.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/core.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/custom.js"></script>

</body>

</html>