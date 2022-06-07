 <?php

    StaticFunctions::new_session();
    $Profile = $_SESSION['ProfileSession'];

    $List1 = [
        StaticFunctions::lang('111_discover') => PATH . 'browse',
        StaticFunctions::lang('109_series') => PATH . 'browse/series',
        StaticFunctions::lang('108_movies') => PATH . 'browse/movies',
        StaticFunctions::lang('115_last') => PATH . 'browse/my/watched',
        StaticFunctions::lang('114_my-wish') => PATH . 'browse/my/list',
    ];

    $List2 = [];

    $Genres = $db->query("SELECT * FROM genres", PDO::FETCH_ASSOC);
    if ($Genres->rowCount()) {
        foreach ($Genres as $row) {
            $List2[StaticFunctions::GenreTranslation($row['genres_name'], $row['genre_translations'])] = PATH . 'browse/genre/87654' . $row['id'];
        }
    }

    $UserList = [
        'WatchList' => [],
        'LikeList'  => []
    ];
    $Me = StaticFunctions::get_id();
    $ProfileID = StaticFunctions::GetProfileId();
    $CheckUserData = $db->query("SELECT * FROM users_data WHERE user_id = '{$ProfileID}'")->fetch(PDO::FETCH_ASSOC);
    if (!$CheckUserData) {
        $InsertData = $db->prepare("INSERT INTO users_data SET
        user_id = ?,
        my_list = ?,
        watch_list = ?");
        $insert = $InsertData->execute(array(
            $ProfileID, json_encode([]), json_encode([])
        ));
    } else {
        $UserList = [
            'WatchList' => json_decode($CheckUserData['watch_list'], true),
            'LikeList'  => json_decode($CheckUserData['my_list'], true)
        ];
    }


    ?>
 <header data-current-header="browse" class="w-100">
     <nav class="navbar fixed-top navbar-expand nav-cont">
         <span class="leftmenutrigger">
             <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                 <g>
                     <path d="M61.3,29.3H2.7C1.1,29.3,0,30.4,0,32c0,1.6,1.1,2.7,2.7,2.7h58.7c1.6,0,2.7-1.1,2.7-2.7C64,30.4,62.9,29.3,61.3,29.3z" />
                     <path d="M61.3,48H2.7C1.1,48,0,49.1,0,50.7c0,1.6,1.1,2.7,2.7,2.7h58.7c1.6,0,2.7-1.1,2.7-2.7C64,49.1,62.9,48,61.3,48z" />
                     <path d="M2.7,16h58.7c1.6,0,2.7-1.1,2.7-2.7s-1.1-2.7-2.7-2.7H2.7c-1.6,0-2.7,1.1-2.7,2.7S1.1,16,2.7,16z" />
                 </g>
             </svg>
         </span>
         <a href="/browse" class="logo-nav">
             <img src="<?= PATH ?>assets/netflux/images/logo.png" alt="Netflux Logo">
         </a>

         <div class="mobile-menu-background d-lg-none"></div>

         <div class="d-block d-lg-none navbar-nav side-nav" style="left: 0px">
             <ul class="mobile-nav-settings">
                 <li class="mobile-nav-item">
                     <a class="mobile-profile no-barba" href="javascript:;" onclick="window.location='<?= PATH ?>profiles';return false;">
                         <img width="50" src="<?= $Profile->profile_avatar ?>" alt="">
                         <p>
                             <span class="d-block"><?= $Profile->profile_name ?></span>
                             <span class="d-block switch-text"><?= StaticFunctions::lang('329_change') ?></span>
                         </p>
                     </a>
                 </li>
                 <li class="">
                     <a class="nav-link" href="/account"><?= StaticFunctions::lang('330_account') ?></a>
                 </li>
                 <li class="">
                     <a class="nav-link" href="/log-out"><?= StaticFunctions::lang('327_sign') ?></a>
                 </li>
             </ul>
             <ul class="mobile-nav-main">
                 <?php

                    foreach ($List1 as $key => $link) {
                        $ia = '';

                        echo '<li data-link="' . $link . '" class="' . $ia . '">
                     <a class="nav-link" href="' . $link . '">' . stripslashes($key) . '
                     </a>
                    </li>';
                    }

                    ?>

             </ul>
             <ul class="mobile-nav-genre">
                 <?php

                    foreach ($List2 as $key => $link) {
                        $ia = '';

                        echo '<li data-link="' . $link . '" class="' . $ia . '">
                     <a class="nav-link" href="' . $link . '">' . stripslashes($key) . '
                     </a>
                    </li>';
                    }

                    ?>
             </ul>
         </div>
         <div class="collapse navbar-collapse" id="navbarText">

             <ul class="navbar-nav d-none d-lg-flex">
                 <?php

                    foreach ($List1 as $key => $link) {
                        $ia = 'nav-item';

                        echo '<li data-link="' . $link . '" class="' . $ia . '">
                     <a class="nav-link" href="' . $link . '">' . stripslashes($key) . '
                     </a>
                    </li>';
                    }

                    $SearchQuery = '';
                    if (isset($_GET['q']) && StaticFunctions::clear($_GET['q']) != '') {
                        $SearchQuery = StaticFunctions::clear($_GET['q']);
                    }

                    ?>
             </ul>
             <ul class="navbar-nav ml-auto align-items-center nav-icons">

                 <li class="nav-item">
                     <div class="search-box"><i class="search-box__icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                                 <path d="M62.9,56.5L45.9,42.7c7.2-9.9,6.1-23.7-2.7-32.5C38.4,5.3,32,2.7,25.3,2.7S12.3,5.3,7.5,10.1S0,21.3,0,28s2.7,13.1,7.5,17.9
	c5.1,5.1,11.5,7.5,17.9,7.5c6.1,0,12.3-2.1,17.1-6.7l17.3,14.1c0.5,0.5,1.1,0.5,1.6,0.5c0.8,0,1.6-0.3,2.1-1.1
	C64.3,59.2,64.3,57.6,62.9,56.5z M25.3,48c-5.3,0-10.4-2.1-14.1-5.9C7.5,38.4,5.3,33.3,5.3,28s2.1-10.4,5.9-14.1S20,8,25.3,8
	s10.4,2.1,14.1,5.9s5.9,8.8,5.9,14.1s-2.1,10.4-5.9,14.1C35.7,45.9,30.7,48,25.3,48z" />
                             </svg></i>
                         <input value="<?= StaticFunctions::say($SearchQuery) ?>" class="search-box__input" placeholder="<?= StaticFunctions::lang('331_titles-persons') ?>">
                     </div>
                 </li>
                 <li class="nav-item d-none d-lg-block">
                     <a class="nav-link" href="javascript:;">
                         <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve">
                             <path d="M58.7,53.3l-2.1-3.2c-0.3-0.5-0.5-1.1-0.5-1.6V27.2c0-6.1-2.7-11.7-7.5-16C44.8,7.5,40,5.3,34.7,4.8V2.7
	C34.7,1.1,33.6,0,32,0s-2.7,1.1-2.7,2.7v2.1h-0.3C17.1,6.1,8,16,8,27.5v20.8c0,1.1-0.1,1.1-0.4,1.7l-2,3.4c-0.8,1.1-0.8,2.7,0,3.7
	c0.5,1.1,1.9,1.6,2.9,1.6h20.8v2.7c0,1.5,1.2,2.7,2.6,2.7c1.5,0,2.7-1.2,2.7-2.7v-2.7h20.8c1.3,0,2.4-0.5,3.2-1.6
	C59.2,55.7,59.2,54.4,58.7,53.3z M11.7,53.3l0.5-0.8c0.5-1.1,1.1-2.4,1.1-3.7V27.5c0-8.8,6.9-16.3,16.3-17.3
	c5.6-0.5,11.2,1.1,15.5,4.8c3.7,3.2,5.6,7.5,5.6,12.3v21.3c0,1.6,0.5,3.2,1.3,4.5l0.3,0.3H11.7z" />
                         </svg>
                     </a>
                 </li>
                 <?php

                    if (defined('UserType') && UserType == 'admin') :
                        echo '<li class="nav-item">
                     <a target="_blank" title="' . StaticFunctions::lang('332_admin') . '" class="nav-link no-barba" href="' . PATH . 'admin">
                         <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
                             style="enable-background:new 0 0 512 512;" xml:space="preserve">
                             <g>
                                 <g>
                                     <path d="M235.082,392.745c-5.771,0-10.449,4.678-10.449,10.449v4.678c0,5.771,4.678,10.449,10.449,10.449
			c5.77,0,10.449-4.678,10.449-10.449v-4.678C245.531,397.423,240.853,392.745,235.082,392.745z" />
                                 </g>
                             </g>
                             <g>
                                 <g>
                                     <path d="M492.948,313.357l-31.393-25.855c1.58-10.4,2.38-20.968,2.38-31.502c0-10.534-0.8-21.104-2.381-31.504l31.394-25.856
			c10.032-8.262,12.595-22.42,6.099-33.66L456.35,91.029c-4.704-8.173-13.479-13.25-22.903-13.25c-3.19,0-6.326,0.573-9.302,1.695
			l-38.109,14.274c-16.546-13.286-34.848-23.869-54.55-31.54l-6.683-40.082C322.676,9.306,311.701,0,298.704,0h-85.408
			C200.3,0,189.324,9.307,187.2,22.119l-6.684,40.088c-19.703,7.673-38.007,18.255-54.553,31.542L87.898,79.492
			c-2.999-1.138-6.14-1.715-9.338-1.715c-9.414,0-18.191,5.074-22.903,13.241l-42.702,73.96
			c-6.499,11.244-3.935,25.403,6.097,33.664l31.394,25.855c-1.58,10.4-2.38,20.969-2.38,31.503c0,10.534,0.8,21.103,2.38,31.503
			l-31.394,25.856c-10.032,8.262-12.595,22.42-6.099,33.66l42.703,73.963c4.716,8.171,13.492,13.247,22.904,13.247
			c3.205,0,6.352-0.581,9.294-1.703l38.107-14.275c16.547,13.287,34.85,23.87,54.551,31.541l6.682,40.075
			C189.316,502.692,200.293,512,213.297,512h85.408c12.991,0,23.967-9.304,26.096-22.118l6.683-40.089
			c19.705-7.673,38.008-18.255,54.554-31.542l38.07,14.261c2.999,1.137,6.141,1.713,9.336,1.713c9.411,0,18.185-5.074,22.9-13.241
			l42.703-73.962C505.543,335.776,502.979,321.619,492.948,313.357z M298.704,491.102H245.53v-49.427
			c0-5.771-4.678-10.449-10.449-10.449c-5.771,0-10.449,4.678-10.449,10.449v49.427h-10.922V376.504
			c13.606,4.844,28.061,7.375,42.865,7.382c0.003,0,0.066,0,0.07,0c14.852,0,29.325-2.528,42.928-7.376v114.515h0.001
			C299.289,491.069,299,491.102,298.704,491.102z M256.642,362.988h-0.057c-58.964-0.029-106.933-48.026-106.932-106.99
			c0.001-34.314,16.175-65.814,43.158-85.745v76.229c0,3.671,1.926,7.072,5.073,8.96l53.381,32.027
			c3.309,1.984,7.443,1.984,10.752,0l53.381-32.027c3.147-1.889,5.073-5.29,5.073-8.96v-76.229
			c26.983,19.931,43.159,51.432,43.157,85.747c0,28.528-11.143,55.382-31.374,75.614
			C312.022,351.846,285.169,362.988,256.642,362.988z M480.949,336.57l-42.705,73.965c-1.326,2.296-4.122,3.423-6.769,2.42
			l-43.772-16.397c-3.557-1.331-7.555-0.63-10.444,1.834c-16.925,14.428-36.026,25.589-56.79,33.212v-64.779
			c9.585-5.551,18.513-12.386,26.56-20.434c24.179-24.18,37.495-56.281,37.495-90.391c0.001-48.242-26.729-91.831-69.76-113.754
			c-3.239-1.651-7.103-1.498-10.203,0.401c-3.099,1.9-4.989,5.274-4.989,8.909v89.011l-42.932,25.759l-42.932-25.759v-89.011
			c0-3.635-1.89-7.009-4.989-8.909c-3.099-1.899-6.963-2.05-10.203-0.401c-43.03,21.922-69.76,65.51-69.762,113.752
			c-0.001,34.743,13.583,67.154,38.247,91.26c7.858,7.68,16.53,14.23,25.809,19.585v65.235
			c-21.258-7.63-40.795-18.958-58.071-33.684c-1.922-1.638-4.333-2.497-6.78-2.497c-1.232,0-2.473,0.217-3.663,0.664l-43.83,16.419
			c-0.613,0.234-1.255,0.353-1.905,0.353c-1.969,0-3.81-1.071-4.805-2.796l-42.706-73.968c-1.365-2.361-0.822-5.337,1.288-7.076
			L68.389,299.8c2.926-2.411,4.318-6.216,3.635-9.944c-2.03-11.12-3.061-22.509-3.061-33.856c0-11.346,1.03-22.736,3.063-33.854
			c0.681-3.729-0.709-7.535-3.636-9.944l-36.051-29.691c-2.112-1.74-2.654-4.716-1.287-7.08l42.705-73.966
			c1.323-2.294,4.109-3.429,6.769-2.419l43.772,16.396c3.555,1.33,7.554,0.63,10.444-1.833
			c17.417-14.847,37.129-26.244,58.589-33.876c3.576-1.272,6.182-4.382,6.805-8.126l7.679-46.059
			c0.446-2.694,2.752-4.649,5.482-4.649h85.408c2.73,0,5.036,1.955,5.485,4.656l7.679,46.053c0.624,3.744,3.23,6.856,6.806,8.126
			c21.459,7.631,41.17,19.027,58.586,33.874c2.89,2.463,6.888,3.165,10.444,1.833l43.794-16.405c0.631-0.238,1.287-0.358,1.95-0.358
			c1.97,0,3.805,1.064,4.798,2.789l42.706,73.967c1.365,2.361,0.822,5.337-1.288,7.076l-36.052,29.692
			c-2.926,2.411-4.318,6.215-3.635,9.944c2.03,11.118,3.061,22.509,3.061,33.855c0,11.346-1.03,22.735-3.063,33.853
			c-0.681,3.728,0.709,7.535,3.636,9.944l36.051,29.691C481.774,331.227,482.316,334.205,480.949,336.57z" />
                                 </g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                             <g>
                             </g>
                         </svg>
                     </a>
                 </li>';
                    endif;

                    ?>
                 <li class="nav-item dropdown d-none d-lg-block">
                     <a class="nav-link dropdown-toggle" href="javascript:;" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <img width="32" src="<?= $Profile->profile_avatar ?>" alt="">
                     </a>
                     <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                         <a class="dropdown-item profile" href="<?= PATH ?>account">
                             <img src="<?= $Profile->profile_avatar ?>" width="32" alt="">
                             <span class="profile-name"><?= $Profile->profile_name ?></span>
                         </a>

                         <?php

                            $OtherProfiles = $db->query("SELECT * from profiles WHERE user_id='{$Me}' and status=1 and id != '{$ProfileID}' order by id ASC ", PDO::FETCH_ASSOC);
                            if ($OtherProfiles->rowCount()) {
                                foreach ($OtherProfiles as $key => $row) {
                                    echo '<a class="dropdown-item profile no-barba" onclick="window.location=\'' . PATH . 'profiles?switch=' . $row['profile_token'] . '\';" href="javascript:;">
                             <img src="' . $row['profile_avatar'] . '" width="32" alt="">
                         <span class="profile-name">' . StaticFunctions::say($row['profile_name']) . '</span>
                         </a>';
                                }
                            }

                            ?>

                         <a class="dropdown-item profile no-barba" href="<?= PATH ?>profiles/manage">
                             <?= Staticfunctions::lang('519_profile') ?>
                         </a>
                         <a class="dropdown-item profile no-barba" href="<?= PATH ?>profiles">
                             <?= Staticfunctions::lang('537_change') ?>
                         </a>
                         <div class="dropdown-divider"></div>
                         <?php
                            if (defined('UserType') && UserType == 'admin') {
                                echo '<a class="dropdown-item dropdown-links no-barba"
                             href="' . PATH . 'admin">' . StaticFunctions::lang('332_admin') . '</a>
                             <div class="dropdown-divider"></div>
                             ';
                            }
                            ?>
                         <a class="dropdown-item dropdown-links font-weight-bold" href="<?= PATH ?>account"><?= StaticFunctions::lang('330_account') ?></a>
                         <a class="dropdown-item dropdown-links font-weight-bold"><?= Staticfunctions::lang('539_help') ?></a>
                         <a class="dropdown-item dropdown-links font-weight-bold no-barba" href="javascript:;" onclick="window.location='/log-out';"><?= Staticfunctions::lang('538_sign-out-of') ?></a>
                     </div>
                 </li>
             </ul>
         </div>
     </nav>
 </header>