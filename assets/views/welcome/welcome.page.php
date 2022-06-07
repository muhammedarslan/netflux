<?php

$PageCss = [];
$PageJs = [
    '/assets/netflux/js/welcome.js'
];

StaticFunctions::BarbaLoaded($PageCss, $PageJs);
?>
<!DOCTYPE html>
<html lang="<?= LANG ?>">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $__PageTitle ?></title>

    <link rel="icon" href="<?= PATH ?>assets/media/fav.ico" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link data-rmv='rmv' rel="stylesheet" href="<?= PATH ?>assets/netflux/css/bootstrap.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="<?= PATH ?>assets/netflux/css/main.css">

    <!-- Google Fonts -->
    <link data-rmv='rmv' href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap"
        rel="stylesheet">
    <script>
    var InternalAjaxHost = '<?= PROTOCOL . DOMAIN . PATH ?>';
    </script>
</head>

<body data-barba="wrapper">

    <div class="PureBlack" style="height:700px;display:block;"></div>

    <main style="display: none;" class="MainContent" data-barba="container"
        data-barba-easy="<?= $PageOptions['View'] . LANG ?>">



        <style type="text/css">
        .welcome-section {
            margin: 0;
            font-family: 'Open Sans', Arial, sans-serif;
            padding: 0;
            font-weight: 700;
            position: absolute;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            top: 0;
            left: 0;
            background-color: #000;
            overflow: hidden;
        }

        .fly-in-text {
            text-align: center;
        }

        .welcome-section .content-wrap {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate3d(-50%, -50%, 0);
        }

        .welcome-section .content-wrap .fly-in-text {
            margin-bottom: -25px;
            list-style: none;
        }

        .welcome-section .content-wrap .fly-in-text li {
            display: inline-block;
            margin-right: 30px;
            font-size: 5em;
            color: #fff;
            opacity: 1;
        }

        .welcome-section .content-wrap .fly-in-text li:last-child {
            margin-right: 0;
        }

        .welcome-section .content-wrap .enter-button {
            display: block;
            text-align: center;
            margin-left: 50px;
            font-size: 1em;
            text-decoration: none;
            color: #adff2f;
            opacity: 1;
            transition: all 1s ease 0s;
        }

        /*
        .welcome-section.content-hidden .content-wrap .fly-in-text li {
            opacity: 0;
        }

        .welcome-section.content-hidden .content-wrap .fly-in-text li:nth-child(1) {
            transform: translate3d(-100px, 0, 0);
        }

        .welcome-section.content-hidden .content-wrap .fly-in-text li:nth-child(2) {
            transform: translate3d(100px, 0, 0);
        }

        */
        .welcome-section.content-hidden .content-wrap .enter-button {
            opacity: 0;
            transform: translate3d(0, -30px, 0);
        }


        @media (min-width: 800px) {
            .welcome-section .content-wrap .fly-in-text li {
                font-size: 10em;
            }

            .welcome-section .content-wrap .enter-button {
                font-size: 1.5em;
            }
        }
        </style>
        <div class="welcome-section content-hidden">
            <div class="content-wrap">
                <ul class="fly-in-text">
                    <?php

                    $SayHi = StaticFunctions::lang('441_hello');

                    $chars = str_split($SayHi);
                    foreach ($chars as $char) {
                        echo '<li>' . $char . '</li>';
                    }

                    ?>
                </ul>
                <?php
                $UserID = StaticFunctions::get_id();
                $UserQuery = $db->query("SELECT * FROM users WHERE    id = '{$UserID}'")->fetch(PDO::FETCH_ASSOC);
                $PacketID = $UserQuery['user_packet'];
                $Packet = $db->query("SELECT * FROM packets WHERE id = '{$PacketID}'")->fetch(PDO::FETCH_ASSOC);
                ?>

                <a href="javascript:;" class="enter-button"><?= $Packet['trial_period'] ?>
                    <?= StaticFunctions::lang('439_launched-for-free-with-the-day') ?></a>
                <a style="margin-top: 10px;" href="javascript:;"
                    class="enter-button"><?= StaticFunctions::lang('440_enjoy') ?></a>
            </div>
        </div>


    </main>

    <script src="<?= PATH ?>assets/netflux/js/jquery-3.5.1.min.js" crossorigin="anonymous">
    </script>
    <script src="<?= PATH ?>assets/netflux/js/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="<?= PATH ?>assets/netflux/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="<?= PATH ?>assets/netflux/js/lazyload.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/bootstrap-validate.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/barba.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/core.js"></script>
    <script src="<?= PATH ?>assets/netflux/js/custom.js"></script>


</body>

</html>