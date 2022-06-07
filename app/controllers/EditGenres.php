<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$GenresName = StaticFunctions::post('edit_genres_name');
$GenresID   = StaticFunctions::post('edit_id');

if ($GenresName == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => true
    ]);
    exit;
}


$SeoAktor = StaticFunctions::seo_link($GenresName);

$InsertData = $db->prepare("UPDATE genres SET
genres_name = ?,
genres_anchor = ? WHERE id=$GenresID  ");
$insert = $InsertData->execute(array(
    $GenresName, $SeoAktor
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('50_the-genre-was-successfully'),
    'clearInput' => false,
    'refreshTable' => true
]);
