<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$GenresName = StaticFunctions::post('genres_name');

if ($GenresName == '') {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('39_please-do-not-leave-blank'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$CheckData = $db->query("SELECT * FROM genres WHERE genres_name = '{$GenresName}'")->fetch(PDO::FETCH_ASSOC);
if ($CheckData) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('78_this-type-has-already-been'),
        'clearInput' => true,
        'closeModal' => true,
        'refreshTable' => false
    ]);
    exit;
}

$SeoAktor = StaticFunctions::seo_link($GenresName);

$InsertData = $db->prepare("INSERT INTO genres SET
genres_name = ?,
genres_anchor = ?");
$insert = $InsertData->execute(array(
    $GenresName, $SeoAktor
));

echo StaticFunctions::JsonOutput([
    'label' => 'success',
    'text' => StaticFunctions::lang('79_the-type-was-successfully'),
    'clearInput' => true,
    'refreshTable' => true
]);
