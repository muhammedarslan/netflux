<?php

$App->get('/', function () {
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		StaticFunctions::NoBarba();
		header("Location:" . PATH . "browse");
		exit;
	}
	if (isset($_COOKIE['RMB']) && $_COOKIE['RMB'] != null) {
		StaticFunctions::NoBarba();
		header("Location:" . PATH . "browse");
		exit;
	}
	$PageOptions = [
		'Title'  => StaticFunctions::lang('1_netflux'),
		'Params' => [],
		'View'   => 'home',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/web-service', function () {
	require_once StaticFunctions::View('V' . '/page.403.php');
});

$App->get('/web-service/(.*?)', function () {
	require_once StaticFunctions::View('V' . '/page.403.php');
});

$App->get('/login/admin', function () {
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		header("Location:" . PATH . "admin/dashboard");
		exit;
	}
	$PageOptions = [
		'Title'  => StaticFunctions::lang('1_netflux17'),
		'Params' => [],
		'View'   => 'login',
		'Class'  => 'admin',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/login/(.*?)', function ($jwt) {
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		header("Location:" . PATH . "browse");
		exit;
	}
	$PageOptions = [
		'Title'  => StaticFunctions::lang('1_netflux'),
		'Params' => [$jwt],
		'View'   => 'login',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/log-out', function () {
	StaticFunctions::NoBarba();
	$PageOptions = [
		'Title'  => StaticFunctions::lang('1_netflux18'),
		'Params' => [],
		'View'   => 'log-out',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
	exit;
});

$App->post('/web-service/(.*?)', function ($Req) {
	require_once CDIR . '/validate.ajax.requests.php';
});

$App->get('/login', function () {
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		header("Location:" . PATH . "browse");
		exit;
	}
	$PageOptions = [
		'Title'  => StaticFunctions::lang('1_netflux'),
		'Params' => [],
		'View'   => 'login',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/signup', function () {
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		header("Location:" . PATH . "browse");
		exit;
	}
	$PageOptions = [
		'Title'  => StaticFunctions::lang('1_netflux'),
		'Params' => [],
		'View'   => 'register',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});
$App->get('/signup/(.*?)', function () {
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		header("Location:" . PATH . "browse");
		exit;
	}
	$PageOptions = [
		'Title'  => StaticFunctions::lang('1_netflux'),
		'Params' => [],
		'View'   => 'register',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/go', function () {
	sleep(1);
	if (isset($_GET['nextpage'])) {
		header("Location:" . $_GET['nextpage']);
	} else {
		header("Location:" . PATH);
	}
	exit;
});

$App->get('/social-login/with/(.*?)', function ($with) {
	global $db;
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		header("Location:" . PATH . "browse");
		exit;
	}
	require_once CDIR . '/class.social.login.php';
	$SocialLogin = new NetfluxSocialLogin();
	$SocialLogin->setFacebookApp($db);
	$SocialLogin->go($with);
	exit;
});

$App->get('/social-callback/(.*?)', function ($with) {
	global $db;
	StaticFunctions::new_session();
	if (isset($_SESSION['CheckSession']) && $_SESSION['CheckSession'] == 'active') {
		header("Location:" . PATH . "browse");
		exit;
	}
	require_once CDIR . '/class.social.login.php';
	$SocialLogin = new NetfluxSocialLogin();
	$SocialLogin->setFacebookApp($db);
	$SocialLogin->callback($with);
});

$App->get('/billing/callback/(.*?)', function ($token) {
	global $db;
	require_once CORE_DIR . '/payments/callback.stripe.php';
});

$App->get('/billing/stripe/failed', function () {
	require_once StaticFunctions::View('V' . '/payment.failed.php');
	exit;
});