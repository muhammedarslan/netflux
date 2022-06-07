<?php

// Check the param and change language if is valid.
if (isset($_GET['hl'])) :
    AppLanguage::NoBarba();
    AppLanguage::SetLang(AppLanguage::clear($_GET['hl']));
endif;

// Get and set App Language.
$GetCurrentLang = AppLanguage::getLang();
define('LANG', $GetCurrentLang);


$AllowedLangs = AppLanguage::GetAllowedLangs();
$LangFile = (isset($AllowedLangs[LANG])) ? $AllowedLangs[LANG]['LangFile'] : null;

if ($LangFile != null && file_exists(APP_DIR . '/languages/' . $LangFile . '.php')) :
    require_once APP_DIR . '/languages/' . $LangFile . '.php';

endif;
