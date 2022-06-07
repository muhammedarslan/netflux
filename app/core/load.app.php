<?php

// System components.
require_once APP_DIR  . '/vendor/autoload.php';
require_once CDIR     . '/class.language.php';
require_once CORE_DIR . '/detect.lang.php';
require_once CDIR     . '/class.functions.php';
require_once CDIR     . '/class.notifications.php';
require_once CDIR     . '/class.billing.manager.php';
require_once CDIR     . '/class.session.manager.php';
require_once CDIR     . '/class.profile.manager.php';
require_once CDIR     . '/validate.request.php';

try {

	// System core.
	require_once CORE_DIR . '/db.php';
	require_once CORE_DIR . '/login.check.php';
	require_once CDIR     . '/class.route.php';
	require_once CORE_DIR . '/route.map.php';
} catch (Exception $e) {

	// System down.
	StaticFunctions::system_down();
}