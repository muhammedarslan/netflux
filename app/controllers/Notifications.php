<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();
$Me = StaticFunctions::get_id();

$NotifArray =  AppNotifications::GetNotifications($Me, $db);

echo $NotifArray;