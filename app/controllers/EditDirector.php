<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$DirectorName = StaticFunctions::post('edit_director_name');
$DirectorID   = StaticFunctions::post('edit_id');

if ($DirectorName == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'danger',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => true
    ]);
    exit;
}


$SeoDirector = StaticFunctions::seo_link($DirectorName);

$InsertData = $db->prepare("UPDATE directors SET
director_name = ?,
director_anchor = ? WHERE id=$DirectorID  ");
$insert = $InsertData->execute(array(
    $DirectorName, $SeoDirector
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('43_the-director-was-successfully'),
    'clearInput' => false,
    'refreshTable' => true
]);
