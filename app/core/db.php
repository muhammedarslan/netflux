<?php


// Try to connect Db.
try {
	$db = new PDO("mysql:host=" . DB_HST . ";dbname=" . DB_NME . ";charset=" . DB_CHR, DB_USR, DB_PWD);
} catch (PDOException $e) {

	StaticFunctions::system_down();
	exit;
}

// Db errors in development mode.
if (Debug == true) :
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
endif;