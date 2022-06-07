<?php

StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();
$Type = StaticFunctions::post('DataType');

$Type2Database = [
    'Actor' => 'actors',
    'Director' => 'directors',
    'User' => 'users',
    'Genres' => 'genres',
    'Plan' => 'packets',
    'Lang' => 'languages',
    'Movie' => 'series_and_movies',
    'Currency' => 'currencies',
    'Avatar' => 'avatars'
];

if (isset($Type2Database[$Type])) {

    $DbTableName = $Type2Database[$Type];

    try {
        $DeleteSingleData = $db->prepare("DELETE FROM $DbTableName WHERE id = :id");
        $delete = $DeleteSingleData->execute(array(
            'id' => StaticFunctions::post('DataId')
        ));
        echo StaticFunctions::ApiJson([
            'status' => 'success',
            'label' => StaticFunctions::lang('429_successful'),
            'text'  => StaticFunctions::lang('38_data-has-been-successfully'),
            'textButton' => 'Tamam'
        ]);
        exit;
    } catch (\Throwable $th) {
        //throw $th;
    }
}


echo StaticFunctions::ApiJson([
    'status' => 'failed',
    'textButton' => 'Tamam'
]);
exit;