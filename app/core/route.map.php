<?php

StaticFunctions::new_session();


require_once CORE_DIR . '/route/route.general.php';

if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
	require_once CORE_DIR . '/route/route.browse.php';

	if (defined('UserType') && UserType == 'admin') {
		require_once CORE_DIR . '/route/route.admin.php';
	}
}
