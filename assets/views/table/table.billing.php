<?php

require_once CDIR . '/class.set.table.php';
if (!isset($PageCss)) $PageCss = [];
if (!isset($PageJs))  $PageJs  = [];

$Table = new NetfluxDataTable();
$Table->setTitle($TableTitle);
$Table->setID($TableID);
$Table->setOptions([
    'Search' => false,
    'Export' => false,
    'PageLength' => [
        'Start' => 4,
        'Menu'  => '[4, 10, 15, 20,25]'
    ],
    'Order' => [
        'Order' => 0,
        'Type' => 'desc'
    ]
]);
$PageCss = $Table->setCss($PageCss);
$PageJs  = $Table->setJs($PageJs);
if ($Table->CheckAjax()) :
    $Table->getContent($db);
endif;

$Table->setHeaders([
    [
        'Name' => '#',
        'Orderable' => true,
        'TextCenter' => true,
        'HideMobile' => true,
        'AlwaysShow' => false,
        'Export' => false,
        'Render' => 'bold'
    ],
    [
        'Name' => StaticFunctions::lang('26_payment'),
        'Orderable' => false,
        'TextCenter' => true,
        'HideMobile' => true,
        'AlwaysShow' => false,
        'Export' => false,
        'Render' => 'bold'
    ],
    [
        'Name' => StaticFunctions::lang('27_subscription'),
        'Orderable' => true,
        'TextCenter' => true,
        'HideMobile' => false,
        'AlwaysShow' => false,
        'Export' => false,
        'Render' => 'bold'
    ],
    [
        'Name' => StaticFunctions::lang('28_invoice'),
        'Orderable' => true,
        'TextCenter' => true,
        'HideMobile' => true,
        'AlwaysShow' => false,
        'Export' => false,
        'Render' => 'bold'
    ],
    [
        'Name' => StaticFunctions::lang('420_subscription'),
        'Orderable' => true,
        'TextCenter' => true,
        'HideMobile' => true,
        'AlwaysShow' => false,
        'Export' => false,
        'Render' => 'bold'
    ],
    [
        'Name' => StaticFunctions::lang('421_subscription'),
        'Orderable' => true,
        'TextCenter' => true,
        'HideMobile' => true,
        'AlwaysShow' => false,
        'Export' => false,
        'Render' => 'bold'
    ]
]);

if ($Table->CheckOptions()) :
    header('Content-Type: application/javascript');
    echo $Table->GetOptions();
    exit;
endif;