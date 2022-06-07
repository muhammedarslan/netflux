<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>Netflux</title>

    <link rel="icon" href="/assets/media/fav.ico" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link data-rmv='rmv' rel="stylesheet" href="/assets/netflux/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.plyr.io/3.6.3/plyr.css">
    <link rel="stylesheet" type="text/css" href="/assets/netflux/css/slick.min.css">

    <link rel="stylesheet" type="text/css" href="/assets/netflux/css/tingle2.css">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/netflux/css/main.css?t=1609430199">

    <!-- Google Fonts -->
    <link data-rmv='rmv' href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap"
        rel="stylesheet">
</head>

<body data-barba="wrapper">


    <header data-current-header="classic" class="position-absolute w-100 pt-1">
        <div class="ml-3 mr-3 ml-md-5 mr-md-5 p-2 pt-4">
            <div class="d-flex align-items-center">
                <a href="/">
                    <img src="/assets/netflux/images/logo.png" alt="Netflux Logo" class="logo">
                </a>

            </div>
        </div>
    </header>
    <main class="MainContent" data-barba="container" data-barba-easy="payment_failed">
        <input type="text" value="classic" hidden id="DataHeader" />
        <section class="box border-0">
            <div style="height:-webkit-fill-available;" class="box-background position-absolute login_background">
                <img src="/assets/netflux/images/background.jpg" alt="Netflux Background">
            </div>

            <div class="login-content d-flex justify-content-center align-items-center">
                <div style="height: 330px;" class="form_background">
                    <div class="d-flex flex-column login login_d">
                        <div class="d-flex flex-column justify-content-center">
                            <h1 class="login-title mb-4 text-center mt-2">
                                Service Unavailable! </h1>
                        </div>

                        <h3 style="font-size: 20px;margin-top:20px;" class="login-title mb-4 text-center">We are
                            currently unable to provide service. Please try again later.</h3>

                        <div class="mt-2">
                            <a href="?reload=<?= time() ?>" class="btn-plans rounded-4">Reload Page</a>
                        </div>



                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/assets/netflux/js/jquery-3.5.1.min.js" crossorigin="anonymous">
    </script>
    <script src="/assets/netflux/js/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="/assets/netflux/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="/assets/netflux/js/lazyload.js"></script>
</body>

</html>
<?php

exit;