<?php

if (!isset($PageCss)) $PageCss = [];
if (!isset($PageJs))  $PageJs  = [];

StaticFunctions::BarbaLoaded($PageCss, $PageJs);

$SomeText = json_encode(
    [
        'Delete1' =>  StaticFunctions::lang('198_are-you'),
        'Delete2' =>  StaticFunctions::lang('199_deleted-data-cannot-be-recovered-do'),
        'Delete3' =>  StaticFunctions::lang('200_go'),
        'Delete4' =>  StaticFunctions::lang('25_close'),
        'Block1' =>  StaticFunctions::lang('198_are-you'),
        'Block2' =>  StaticFunctions::lang('201_this-user-will-be-blocked-do-you-want'),
        'Block3' =>  StaticFunctions::lang('200_go'),
        'Block4' =>  StaticFunctions::lang('25_close'),
        'EditImg1' =>  StaticFunctions::lang('202_what-would-you-like-to'),
        'EditImg2' =>  StaticFunctions::lang('203_you-can-browse-this-image-or-remove-it'),
        'EditImg3' =>  StaticFunctions::lang('204_remove-from'),
        'EditImg4' =>  StaticFunctions::lang('205_browse-the')

    ]
);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= StaticFunctions::lang('206_netflux-control') ?></title>
    <meta name="description" content="description">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= PATH ?>assets/media/fav.ico" type="image/x-icon" />

    <link rel="stylesheet" href="<?= PATH ?>assets/console/css/reset.css">
    <link rel="stylesheet" href="<?= PATH ?>assets/console/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= PATH ?>assets/console/css/slick.css">
    <link rel="stylesheet" href="<?= PATH ?>assets/console/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= PATH ?>assets/console/css/style.css">
    <link rel="stylesheet" href="<?= PATH ?>assets/console/css/toastr.min.css">
    <script>
    var InternalAjaxHost = '<?= PROTOCOL . DOMAIN . PATH ?>';
    var AppLang = '<?= LANG ?>';
    </script>
</head>

<body data-barba="wrapper">

    <?php

    if ($FileName != 'login') {
        require_once StaticFunctions::View('V' . '/admin.menu.php');
    }

    ?>

    <div class="PureBlack" style="height:auto;width:100%;text-align:center;display:flex;">

        <div class="spinner spinner-admin"></div>

    </div>

    <main style="display: none;" class="MainContent" data-barba="container"
        data-barba-easy="<?= $PageOptions['View'] . LANG ?>">