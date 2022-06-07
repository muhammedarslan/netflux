<?php


$App->get('/browse', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('111_discover'),
		'Params' => [],
		'View'   => 'browse',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/profiles', function () {
	StaticFunctions::NoBarba();
	global $db;
	if (isset($_GET['switch']) && $_GET['switch'] != '') :
		$ProfileManager = new ProfileManager();
		$ProfileManager->setDb($db);
		if ($ProfileManager->CheckProfileToken($_GET['switch'])) :
			$ProfileManager->set($_GET['switch']);
			StaticFunctions::go('browse?hl=' . $ProfileManager->ProfileLang());
			exit;
		endif;
	endif;
	require_once StaticFunctions::View('V' . '/profile.selector.php');
	exit;
});

$App->get('/profiles/manage', function () {
	StaticFunctions::NoBarba();
	global $db;
	if (isset($_GET['switch']) && $_GET['switch'] != '') :
		$ProfileManager = new ProfileManager();
		$ProfileManager->setDb($db);
		if ($ProfileManager->CheckProfileToken($_GET['switch'])) :
			$ProfileManager->set($_GET['switch']);
			StaticFunctions::go('browse?hl=' . $ProfileManager->ProfileLang());
			exit;
		endif;
	endif;
	require_once StaticFunctions::View('V' . '/profile.selector.php');
	exit;
});

$App->get('/account', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('112_my'),
		'Params' => [],
		'View'   => 'account',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/subscription/info', function () {
	StaticFunctions::go('browse');
	exit;
});

$App->get('/account/packets', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('113_change'),
		'Params' => [],
		'View'   => 'change.plan',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/search', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('111_discover'),
		'Params' => [],
		'View'   => 'browse.search',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/my/list', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('114_my-wish'),
		'Params' => [],
		'View'   => 'browse.list',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/my/watched', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('115_last'),
		'Params' => [],
		'View'   => 'browse.watched',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/genre/87654(.*?)', function ($id) {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('103_species'),
		'Params' => [$id],
		'View'   => 'browse.genres',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/actor/87654(.*?)', function ($id) {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('116_players'),
		'Params' => [$id],
		'View'   => 'browse.actors',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/director/87654(.*?)', function ($id) {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('101_directors'),
		'Params' => [$id],
		'View'   => 'browse.directors',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/movies', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('108_movies'),
		'Params' => [],
		'View'   => 'browse.movies',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/movies/87654(.*?)', function ($id) {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('108_movies'),
		'Params' => [$id],
		'View'   => 'browse.movies.genre',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/series', function () {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('109_series'),
		'Params' => [],
		'View'   => 'browse.series',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/browse/series/87654(.*?)', function ($id) {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('108_movies'),
		'Params' => [$id],
		'View'   => 'browse.series.genre',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});
$App->get('/browse/87654(.*?)', function ($id) {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('111_discover'),
		'Params' => [$id],
		'View'   => 'browse.single',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});

$App->get('/watch/87654([\d]+)', function ($id) {
	StaticFunctions::go('watch/87654' . $id . '/' . StaticFunctions::WatchReferer());
	exit;
});

$App->get('/watch/87654([\d]+)/(.*?)', function ($id, $Jwt) {
	$PageOptions = [
		'Title'  => StaticFunctions::lang('111_discover'),
		'Params' => [$id, $Jwt],
		'View'   => 'watch',
		'Class'  => 'netflux',
		'BodyE'  => null
	];
	StaticFunctions::load_page($PageOptions);
});