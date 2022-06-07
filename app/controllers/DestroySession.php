<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$_SESSION['CheckSession'] = false;
session_destroy();