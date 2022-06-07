<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$ActorName = StaticFunctions::post('actor_name');

if ($ActorName == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$CheckData = $db->query("SELECT * FROM actors WHERE actor_name = '{$ActorName}'")->fetch(PDO::FETCH_ASSOC);
if ($CheckData) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('72_this-actor-has-already-been'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$SeoAktor = StaticFunctions::seo_link($ActorName);

$InsertData = $db->prepare("INSERT INTO actors SET
actor_name = ?,
actor_anchor = ?");
$insert = $InsertData->execute(array(
    $ActorName, $SeoAktor
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('73_the-actor-was-successfully'),
    'clearInput' => true,
    'refreshTable' => true
]);
