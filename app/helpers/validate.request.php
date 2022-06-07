<?php

if (Debug == true) :
	// Show errors in developer mode.
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
else :
	// Hide errors.
	error_reporting(0);
endif;

// Get visitor's ip address.
if (getenv('HTTP_CLIENT_IP'))             $ipaddress = getenv('HTTP_CLIENT_IP');
else if (getenv('HTTP_X_FORWARDED_FOR'))  $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
else if (getenv('HTTP_X_FORWARDED'))      $ipaddress = getenv('HTTP_X_FORWARDED');
else if (getenv('HTTP_FORWARDED_FOR'))    $ipaddress = getenv('HTTP_FORWARDED_FOR');
else if (getenv('HTTP_FORWARDED'))        $ipaddress = getenv('HTTP_FORWARDED');
else if (getenv('REMOTE_ADDR'))           $ipaddress = getenv('REMOTE_ADDR');
else                                      $ipaddress = 'UNKNOWN';

// Check visitor's ip address.
if (!filter_var($ipaddress, FILTER_VALIDATE_IP)) :
	require_once StaticFunctions::View('V' . '/validate.session.php');
endif;

// Check visitor's browser.
if (!isset($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == '') :
	require_once StaticFunctions::View('V' . '/validate.session.php');
endif;

// Show landing page in maintenance mode.
if (MaintenanceMode == true) :
	StaticFunctions::system_down();
	exit;
endif;

// Remove double or more slashes from url. 
$RequestUrl = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
$RequestUrl = (str_replace(PATH, '/', $RequestUrl) == '') ? '/' : str_replace(PATH, '/', $RequestUrl);

if (mb_strstr($RequestUrl, '//')) :
	header("Location:" . StaticFunctions::RemoveBunchOfSlashes($RequestUrl));
	exit;
endif;
