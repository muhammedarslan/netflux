<?php

if (!isset($PageCss)) $PageCss = [];
if (!isset($PageJs))  $PageJs  = [];

if (defined('UserType') && UserType == 'admin') {
    //StaticFunctions::NoBarba();
}

if (isset($_GET['load']) && StaticFunctions::clear($_GET['load']) == 'header') {
    if (strstr($PageOptions['View'], 'browse')) {
        $HeaderName = 'browse';
        require_once StaticFunctions::View('V' . '/header.browse.php');
    } else if ($PageOptions['View'] == 'account') {
        $HeaderName = 'account';
        require_once StaticFunctions::View('V' . '/header.account.php');
    } else {
        $HeaderName = 'classic';
        require_once StaticFunctions::View('V' . '/header.classic.php');
    }
    exit;
}

StaticFunctions::BarbaLoaded($PageCss, $PageJs);

?>
<!doctype html>
<html lang="<?= LANG ?>">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title><?= StaticFunctions::lang('1_netflux') ?></title>

    <link rel="icon" href="<?= PATH ?>assets/media/fav.ico" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link data-rmv='rmv' rel="stylesheet" href="<?= PATH ?>assets/netflux/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.plyr.io/3.6.3/plyr.css">
    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/slick.min.css">

    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/tingle2.css">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/main.css<?= (Debug) ? '?t='.time() : null ?>">

    <!-- Google Fonts -->
    <link data-rmv='rmv' href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <script>
        var InternalAjaxHost = '<?= PROTOCOL . DOMAIN . PATH ?>';
        var AppLang = '<?= LANG ?>';
    </script>
</head>

<body data-barba="wrapper">


    <?php

    if (strstr($PageOptions['View'], 'browse')) {
        $HeaderName = 'browse';
        require_once StaticFunctions::View('V' . '/header.browse.php');
    } else if ($PageOptions['View'] == 'account') {
        $HeaderName = 'account';
        require_once StaticFunctions::View('V' . '/header.account.php');
    } else {
        $HeaderName = 'classic';
        require_once StaticFunctions::View('V' . '/header.classic.php');
    }
    ?>
    <div class="PureBlack" style="height:100vh;width:100%;text-align:center;display:flex;">
    		
    		<div class="netflux-spinner netflux-spinner-browse"></div>


    </div>
    <main style="display: none;" class="MainContent" data-barba="container" data-barba-easy="<?= $PageOptions['View'] . LANG ?>">
        <input type="text" value="<?= $HeaderName ?>" hidden id="DataHeader" />
