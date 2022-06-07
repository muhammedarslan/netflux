<?php

// Folder defines.
define('ROOT_DIR', __DIR__);
define('APP_DIR', ROOT_DIR . '/app');
define('CORE_DIR', APP_DIR  . '/core');
//define('VDIR', APP_DIR  . '/views');
define('VDIR', ROOT_DIR  . '/assets/views');
define('CDIR', APP_DIR  . '/helpers');
define('TMPDIR', ROOT_DIR . '/assets/tmp');

// App defines.
define('PROTOCOL', 'https://');
define('DOMAIN', 'netflux4.muhammedarslan.net.tr');
define('PATH', '/');
define('PR_NAME', '');

// Database defines.
define('DB_HST', 'localhost');
define('DB_NME', '');
define('DB_CHR', 'utf8');
define('DB_USR', '');
define('DB_PWD', '');

define('MaintenanceMode', false); // Switches all the structures of the project to maintenance mode.
define('Debug', true); // Only in developer mode.

// Load components.
require_once CORE_DIR . '/load.app.php';
// Start project.
$App->run();