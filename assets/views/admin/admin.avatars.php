<?php

$PageCss = [
    '/assets/console/css/grid.css',
    '/assets/console/css/jquery.fancybox.min.css'
];

$PageJs = [
    '/assets/console/js/jquery.fancybox.min.js',
    '/assets/console/js/gallery.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');


?>

<div class="content">

    <div class="row">
        <div style="display: inline-block;" class="col-6">
            <h1><?= StaticFunctions::lang('520_profile') ?></h1>
        </div>
        <div style="display: inline-block;float:right;" class="col-6">
            <a href="javascript:;" class="button js-modal-toggle" data-target="first-modal"><?= StaticFunctions::lang('144_add') ?></a>
        </div>
    </div>
    <br />


    <section style="padding: 50px 0;" class="portfolio" id="portfolio">
        <div class="container-fluid">
            <div class="row">

                <div align="center">
                    <button class="filter-button" data-filter="all"><?= Staticfunctions::lang('527_all') ?></button>
                    <?php

                    $AvatarGroups = $db->query("SELECT avatar_group from avatars group by avatar_group ", PDO::FETCH_ASSOC);
                    if ($AvatarGroups->rowCount()) {
                        foreach ($AvatarGroups as $key => $group) {
                            if ($group['avatar_group'] == 'Klasikler') $group['avatar_group'] = Staticfunctions::lang('518_classics');
                            echo '<button style="margin-right:5px;" class="filter-button" data-filter="' . StaticFunctions::seo_link($group['avatar_group']) . '">' . StaticFunctions::say($group['avatar_group']) . '</button>';
                        }
                    }

                    ?>
                </div>

                <br />
                <?php

                $AvatarGroups = $db->query("SELECT * from avatars ", PDO::FETCH_ASSOC);
                if ($AvatarGroups->rowCount()) {
                    foreach ($AvatarGroups as $key => $group) {
                        if ($group['avatar_group'] == 'Klasikler') $group['avatar_group'] = Staticfunctions::lang('518_classics');

                        echo '<div style="margin-bottom:30px;" class="gallery_product col-sm-2 col-xs-4 filter ' . StaticFunctions::seo_link($group['avatar_group']) . '">
                    <a onclick="DeleteDataGallery(\'Avatar\',' . $group['id'] . ');" href="javascript:;" >
                        <img class="img-responsive lazyload" alt="" src="' . PATH . 'assets/media/box_grey.png" data-src="' . PATH . 'assets' . $group['avatar_path'] . '" />
                        <div class="img-info">
                            <h4>' . $group['avatar_group'] . '</h4>
                        </div>
                    </a>
                </div>';
                    }
                }

                ?>

            </div>
        </div>
    </section>


</div>



<div class="modal js-modal" data-name="first-modal">
    <div class="backdrop"></div>
    <div style="margin-top: 200px;" class="modal-content">
        <div class="hero"><?= StaticFunctions::lang('521_add-new') ?></div>
        <a href="javascript:;" class="close js-modal-close">
            <em class="fa fa-close"></em>
        </a>
        <form onsubmit="SubmitFormGallery(this);" data-source="web-service/new/avatar" action="javascript:;" action="" method="post">

            <div class="fields">

                <div id="avatarField1" class="field">
                    <label>
                        <?= StaticFunctions::lang('522_group-of-the') ?>
                        <select id="avatar_s_g" onchange="GroupSelectGallery();" name="avatar_group_select">
                            <?php

                            $AvatarGroups = $db->query("SELECT avatar_group from avatars group by avatar_group ", PDO::FETCH_ASSOC);
                            if ($AvatarGroups->rowCount()) {
                                foreach ($AvatarGroups as $key => $group) {
                                    $DisplayName = ($group['avatar_group'] == 'Klasikler') ? Staticfunctions::lang('518_classics') : StaticFunctions::say($group['avatar_group']);
                                    echo '<option value="' . $group['avatar_group'] . '" >' . $DisplayName . '</option>';
                                }
                            }
                            ?>
                            <option value="__new__"><?= Staticfunctions::lang('524_create-new') ?></option>
                        </select>
                    </label>
                </div>


                <div id="avatarField2" style="display: none;" class="field">
                    <label>
                        <?= StaticFunctions::lang('522_group-of-the') ?>
                        <input name="avatar_group" placeholder="<?= StaticFunctions::lang('522_group-of-the') ?>" type="text" />
                    </label>
                    <a style="margin:10px;text-decoration:none;" href="javascript:;" onclick="GroupSelectGallery2();"><?= Staticfunctions::lang('525_select') ?></a>
                </div>


                <div class="field">
                    <label>
                        <?= StaticFunctions::lang('523_image') ?>
                        <input required name="avatar_img" accept="image/*" multiple type="file" />
                    </label>
                </div>

            </div>


            <button class="button form_button"><?= StaticFunctions::lang('150_add') ?></button>
            <div style="clear:both"></div>
        </form>
    </div>
</div>


<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
