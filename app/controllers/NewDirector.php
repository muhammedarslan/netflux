<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$DirectorName = StaticFunctions::post('director_name');

if ($DirectorName == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$CheckData = $db->query("SELECT * FROM directors WHERE director_name = '{$DirectorName}'")->fetch(PDO::FETCH_ASSOC);
if ($CheckData) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('74_this-director-has-already-been'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$SeoAktor = StaticFunctions::seo_link($DirectorName);

$InsertData = $db->prepare("INSERT INTO directors SET
director_name = ?,
director_anchor = ?");
$insert = $InsertData->execute(array(
    $DirectorName, $SeoAktor
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('75_the-director-was-successfully'),
    'clearInput' => true,
    'refreshTable' => true
]);
