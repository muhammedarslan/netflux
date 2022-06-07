<?php


$App->get('/admin', function () {
    StaticFunctions::NoBarba();
    header("Location:" . PATH . 'admin/dashboard');
    exit;
});

$App->get('/admin/dashboard', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('99_admin'),
        'Params' => [],
        'View'   => 'dashboard',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/actors', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('100_actors'),
        'Params' => [],
        'View'   => 'actors',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/directors', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('101_directors'),
        'Params' => [],
        'View'   => 'directors',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/users', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('102_users'),
        'Params' => [],
        'View'   => 'users',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/genres', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('103_species'),
        'Params' => [],
        'View'   => 'genres',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/plans', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('104_plans'),
        'Params' => [],
        'View'   => 'plans',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/billing', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('105_paypal-stripe-amp'),
        'Params' => [],
        'View'   => 'billing',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/languages', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('106_language'),
        'Params' => [],
        'View'   => 'languages',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/payments', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('107_payments'),
        'Params' => [],
        'View'   => 'payments',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/movies', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('108_movies'),
        'Params' => [],
        'View'   => 'movies',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/series', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('109_series'),
        'Params' => [],
        'View'   => 'series',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/activities', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('110_activities'),
        'Params' => [],
        'View'   => 'activities',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/currencies', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('461_currencies'),
        'Params' => [],
        'View'   => 'currencies',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/avatars', function () {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('520_profile'),
        'Params' => [],
        'View'   => 'avatars',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});

$App->get('/admin/languages/translate/(.*?)', function ($code) {
    $PageOptions = [
        'Title'  => StaticFunctions::lang('106_language'),
        'Params' => [$code],
        'View'   => 'translate',
        'Class'  => 'admin',
        'BodyE'  => null
    ];
    StaticFunctions::load_page($PageOptions);
});