<?php

class AppLanguage
{

    private static function GetNonLangPages()
    {
        return [
            'web-service',
            'go',
            'profiles',
            'log-out',
            'watch',
            'browse',
            'account',
            'reset-password',
            'social-login',
            'social-callback',
            'billing',
            'admin',
            'stream',
            'subscription'
        ];
    }

    public static function GetAllowedLangs()
    {

        $Json = file_get_contents(APP_DIR . '/languages/languages.json');
        $Decode = json_decode($Json, true);

        return $Decode;
    }

    public static function LanguageJson()
    {

        $AllowedLangs = self::GetAllowedLangs();
        $LangFile = (isset($AllowedLangs[LANG])) ? $AllowedLangs[LANG]['LangFile'] : 'tr_TR';

        if (file_exists(APP_DIR . '/languages/' . $LangFile . '.json')) {
            $FileLocation = APP_DIR . '/languages/' . $LangFile . '.json';
        } else {
            $FileLocation = APP_DIR . '/languages/' . 'tr_TR' . '.json';
        }


        $Json = file_get_contents($FileLocation);
        $Decode = json_decode($Json, true);

        return $Decode;
    }

    public static function LanguageSingleJson($Lang)
    {

        $AllowedLangs = self::GetAllowedLangs();
        $LangFile = (isset($AllowedLangs[$Lang])) ? $AllowedLangs[$Lang]['LangFile'] : null;

        if (file_exists(APP_DIR . '/languages/' . $LangFile . '.json')) {
            $FileLocation = APP_DIR . '/languages/' . $LangFile . '.json';
        } else {
            $Null = self::LanguageJson();
            foreach ($Null as $key => $value) {
                $Null[$key] = '';
            }
            @unlink(APP_DIR . '/languages/' . $LangFile . '.json');
            sleep(1);
            file_put_contents(APP_DIR . '/languages/' . $LangFile . '.json', json_encode($Null));
            $FileLocation = APP_DIR . '/languages/' . $LangFile . '.json';
        }


        $Json = file_get_contents($FileLocation);
        $Decode = json_decode($Json, true);

        return $Decode;
    }

    public static function clear($mVar)
    {
        if (is_array($mVar)) {
            foreach ($mVar as $gVal => $gVar) {
                if (!is_array($gVar)) {
                    $mVar[$gVal] = htmlspecialchars(strip_tags(urldecode(addslashes(stripslashes(stripslashes(trim(htmlspecialchars_decode($gVar))))))));
                } else {
                    $mVar[$gVal] = self::clear($gVar);
                }
            }
        } else {
            $mVar = htmlspecialchars(strip_tags(urldecode(addslashes(stripslashes(stripslashes(trim(htmlspecialchars_decode($mVar))))))));
        }
        return $mVar;
    }

    public static function UrlMaker($Url)
    {
        $UrlExplode = explode('/', rtrim(urldecode(strtok($Url, '?')), '/'));
        if (!isset($UrlExplode[1]) || !in_array($UrlExplode[1], self::GetNonLangPages())) :
            if (!isset($UrlExplode[1]) || !isset(self::GetAllowedLangs()[$UrlExplode[1]])) :
                header("Location:/" . self::getLang() . $_SERVER['REQUEST_URI']);
                exit;
            endif;

            unset($UrlExplode[1]);
            $NewUrl = '';
            foreach ($UrlExplode as $key => $value) {
                $NewUrl .=  $value . '/';
            }

            if (isset($UrlExplode[2]) && in_array($UrlExplode[2], self::GetNonLangPages())) :
                header("Location:" . $NewUrl);
                exit;
            endif;

            $NewUrl = rtrim($NewUrl, '/');
            return $NewUrl;
        else :
            return $Url;
        endif;
    }

    private static function CheckPage()
    {
        $route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
        $route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
        $UrlExplode = explode('/', rtrim(urldecode(strtok($route_path, '?')), '/'));
        if (!isset($UrlExplode[1]) || !in_array($UrlExplode[1], self::GetNonLangPages())) :
            return 'LanguagePage';
        else :
            return 'NonLanguagePage';
        endif;
    }

    private static function GetDefault()
    {
        $PublicDefault = 'tr';

        if (
            isset($_SERVER['HTTP_CF_IPCOUNTRY']) && $_SERVER['HTTP_CF_IPCOUNTRY'] != ''
            && isset(self::GetAllowedLangs()[mb_strtolower(self::clear($_SERVER['HTTP_CF_IPCOUNTRY']))])
        ) :
            $PublicDefault = mb_strtolower(self::clear($_SERVER['HTTP_CF_IPCOUNTRY']));
        else :
            if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) :
                $BrowserLang = mb_strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2));
                if (isset(self::GetAllowedLangs()[$BrowserLang])) :
                    $PublicDefault = $BrowserLang;
                endif;
            endif;
        endif;

        return $PublicDefault;
    }

    public static function CookieLang()
    {
        if (isset($_COOKIE['AppLang']) && isset(self::GetAllowedLangs()[self::clear($_COOKIE['AppLang'])])) :
            return self::clear($_COOKIE['AppLang']);
        else :
            return null;
        endif;
    }

    public static function Db()
    {
        try {
            $db = new PDO("mysql:host=" . DB_HST . ";dbname=" . DB_NME . ";charset=" . DB_CHR, DB_USR, DB_PWD);
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Db error.';
            exit;
        }

        return $db;
    }

    public static function CheckUrlLanguage($UrlLang)
    {
        if (self::CookieLang() != null) :
            if (self::CookieLang() != $UrlLang) :
                return false;
            endif;
        else :
            self::SetLang($UrlLang);
            return true;
        endif;
    }

    public static function NoBarba()
    {
        if (isset($_SERVER['HTTP_X_BARBA'])) {
            http_response_code(401);
            exit;
        }
    }

    public static function SetLang($Lang)
    {
        //self::NoBarba();
        if (isset(self::GetAllowedLangs()[$Lang])) :
            setcookie('AppLang', $Lang, time() + 60 * 60 * 24 * 30, '/');
        endif;

        if (self::CheckPage() == 'LanguagePage') :
            if (isset($_GET['hl'])) :
                $route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
                $route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
                $UrlExplode = explode('/', rtrim(urldecode(strtok($route_path, '?')), '/'));
                unset($UrlExplode[1]);
                $NewUrl = $Lang;
                foreach ($UrlExplode as $key => $value) {
                    $NewUrl .=  $value . '/';
                }
                header("Location:/" . $NewUrl);
                exit;
            endif;
        else :
            if (isset($_GET['hl'])) :
                $route_path = trim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
                header("Location:/" . $route_path);
                exit;
            endif;
        endif;

        return null;
    }

    public static function getLang()
    {
        $Page       = AppLanguage::CheckPage();
        $ReturnLang = AppLanguage::GetDefault();
        $CookieLang = (AppLanguage::CookieLang() != null) ? AppLanguage::CookieLang() : $ReturnLang;

        if ($Page == 'LanguagePage') :

            $route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
            $route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
            $UrlExplode = explode('/', rtrim(urldecode(strtok($route_path, '?')), '/'));
            $UrlLanguage = (isset($UrlExplode[1])) ? $UrlExplode[1] : $CookieLang;

            if (!isset(self::GetAllowedLangs()[$UrlLanguage])) :
                $UrlLanguage = $CookieLang;
            endif;

            if (!self::CheckUrlLanguage($UrlLanguage)) :
                self::SetLang($UrlLanguage);
            endif;
            return $UrlLanguage;
        else :
            return $CookieLang;
        endif;
    }
}