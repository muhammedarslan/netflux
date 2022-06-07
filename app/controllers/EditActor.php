<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$ActorName = StaticFunctions::post('edit_actor_name');
$ActorID   = StaticFunctions::post('edit_id');

if ($ActorName == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => true
    ]);
    exit;
}


$SeoAktor = StaticFunctions::seo_link($ActorName);

$InsertData = $db->prepare("UPDATE actors SET
actor_name = ?,
actor_anchor = ? WHERE id=$ActorID  ");
$insert = $InsertData->execute(array(
    $ActorName, $SeoAktor
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('40_the-actor-was-successfully'),
    'clearInput' => false,
    'refreshTable' => true
]);
