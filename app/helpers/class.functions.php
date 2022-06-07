<?php

class StaticFunctions
{

    public static function go($get)
    {
        $URL = PROTOCOL . DOMAIN . PATH . $get;
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        die(StaticFunctions::lang('3_you-are-being-redirected'));
    }

    public static function go_home()
    {
        $URL = PROTOCOL . DOMAIN . PATH;
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        die(StaticFunctions::lang('3_you-are-being-redirected'));
    }

    public static function reload()
    {
        $URL = $_SERVER['REQUEST_URI'];
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        die(StaticFunctions::lang('3_you-are-being-redirected'));
    }

    public static function new_session()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function system_down()
    {
        http_response_code(503);
        require_once StaticFunctions::View('V' . '/system.down.php');
        exit;
    }

    public static function JsonOutput($data, $ex = '')
    {
        if (is_array($data)) {

            $DataArray = array(
                'HttpStatus' => 200,
                'Content-type' => 'Application/Json',
                'RequestTime' => date('d-m-Y H:i:s') . ' ' . date_default_timezone_get(),
                'TimeUnix'   => time()
            );

            return  json_encode(array_merge($DataArray, $data), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            return  json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    public static function Array2Xml($array, $xml = false)
    {

        if ($xml === false) {
            $xml = new SimpleXMLElement('<response/>');
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                self::Array2Xml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }

    public static function ApiJson($ResponseData)
    {
        if (isset($_GET['format']) && self::clear($_GET['format']) == 'xml') :
            return self::Array2Xml($ResponseData, false);
        else :
            return json_encode($ResponseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        endif;
    }

    public static function LoginRequiredPages($ExplodedUrl)
    {
        $RequiredPages = [
            'browse',
            'watch',
            'account',
            'profiles',
            'stream',
            'subscription'
        ];

        if (in_array($ExplodedUrl[1], $RequiredPages)) :
            return true;
        else :
            return false;
        endif;
    }

    public static function ShowPrice($Price, $Symbol, $Float)
    {
        if ($Float == 'l') {
            return $Symbol . $Price;
        } else {
            return $Price . $Symbol;
        }
    }

    public static function FloatPrice($BasePrice, $FloatType = 0)
    {
        if ($BasePrice == 0) {
            return number_format(0, 2);
        } else {
            switch ($FloatType) {
                case 0:
                    // Yuvarlama
                    return number_format($BasePrice, 2);
                    break;
                case 1:
                    // Tam sayıya yuvarla
                    return number_format(round($BasePrice), 2);
                    break;
                case 2:
                    // Tam sayıya yuvarla kuruşları gizle
                    return number_format($BasePrice);
                    break;
                case 3:
                    // Sonunu 99 kuruş yap
                    $BasePrice = round($BasePrice + 0.01) - 0.01;
                    return number_format($BasePrice, 2);
                    break;
                case 4:
                    // Sonunu 99 kuruş yap aşağı yuvarla
                    $BasePrice = floor($BasePrice) - 0.01;
                    return number_format($BasePrice, 2);
                    break;
                case 5:
                    // Sonunu 99 kuruş yap yukarı yuvarla
                    $BasePrice = ceil($BasePrice) - 0.01;
                    return number_format($BasePrice, 2);
                    break;
                default:
                    return number_format($BasePrice, 2);
                    break;
            }
        }
    }

    public static function RemoveBunchOfSlashes($url)
    {
        $url = PROTOCOL . DOMAIN . PATH . $url;
        $explode = explode('://', $url);
        while (strpos($explode[1], '//'))
            $explode[1] = str_replace('//', '/', $explode[1]);
        return implode('://', $explode);
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

    public static function TrimText($word, $str = 140)
    {
        if (strlen($word) > $str) {
            if (function_exists("mb_substr")) $word = mb_substr($word, 0, $str, "UTF-8") . '...';
            else $word = substr($word, 0, $str) . '...';
        }
        return $word;
    }

    public static function TrimText2($text, $str = 140)
    {
        $text = substr($text, 0, $str) . "...";
        $lastText = strrchr($text, " ");
        $text = str_replace($lastText, "...", $text);
        return $text;
    }

    public static function WatchReferer()
    {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
            $Ref =  self::clear($_SERVER['HTTP_REFERER']);
        } else {
            $Ref =  PROTOCOL . DOMAIN . PATH . 'browse';
        }

        $payload = array(
            'Referer' => $Ref,
            'UnixTime' => time()
        );

        $jwt = \Firebase\JWT\JWT::encode($payload, self::JwtKey());

        return $jwt;
    }

    public static function VideoAdulthoodLevel($Level)
    {
        /* 
          0: "18+ ALL MATURITY LEVELS",
          1: "16+ CONTENTS ONLY",
          2: "ONLY 13+ CONTENTS",
          3: "7+ CONTENTS ONLY",
          4: "GENERAL VIEWER CONTENTS",
          5: "CONTENTS FOR CHILDREN",
        */
        switch ($Level) {
            case '0':
                return '18+';
                break;
            case '1':
                return '16+';
                break;
            case '2':
                return '13+';
                break;
            case '3':
                return '7+';
                break;
            case '4':
                return '__noLevel__';
                break;
            case '5':
                return '__noLevel__';
                break;
            default:
                return '__noLevel__';
                break;
        }
    }

    public static function checkAdulthoodLevel($videoLevel)
    {
        global $db;
        $ProfileID = self::GetProfileId();
        $ProfileLevel = $db->query("SELECT profile_level from profiles WHERE id='{$ProfileID}' ")->fetch(PDO::FETCH_ASSOC)['profile_level'];
        if ($ProfileLevel > $videoLevel) {
            self::go_home();
            exit;
        }
    }

    public static function BrowseReferer()
    {

        $payload = array(
            'Referer' => PROTOCOL . DOMAIN . PATH . trim($_SERVER['REQUEST_URI'], '/')
        );

        $jwt = \Firebase\JWT\JWT::encode($payload, self::JwtKey());

        return $jwt;
    }

    public static function ajax_form($AjaxType)
    {

        if ($AjaxType != 'general') {
            StaticFunctions::new_session();
            if (!isset($_SESSION['CheckSession']) || $_SESSION['CheckSession'] != 'active') {
                header("Content-type: application/json; charset=utf-8");
                http_response_code(403);
                echo StaticFunctions::JsonOutput(array(
                    'HttpStatusCode' => 403,
                    'ErrorMessage'   => 'Access Denied.'
                ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                exit;
            }
        }

        if ($AjaxType == 'admin') {
            if (UserType != 'admin') {
                header("Content-type: application/json; charset=utf-8");
                http_response_code(403);
                echo StaticFunctions::JsonOutput(array(
                    'HttpStatusCode' => 403,
                    'ErrorMessage'   => 'Access Denied.'
                ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                exit;
            }
        }

        return null;
    }

    public static function AjaxCheck()
    {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            return false;
        }

        if (!isset($_SERVER['HTTP_REFERER'])) {
            return false;
        }

        $AjaxDomain = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        if ($AjaxDomain != DOMAIN) {
            return false;
        }

        return true;
    }

    public static function BoolText($bool)
    {
        if ($bool) :
            return 'true';
        else :
            return 'false';
        endif;
    }

    public static function get_id()
    {
        StaticFunctions::new_session();
        if (!isset($_SESSION['UserID'])) {
            http_response_code(401);
            exit;
        }
        if ($_SESSION['UserID'] == '') {
            http_response_code(401);
            exit;
        }
        return $_SESSION['UserID'];
    }

    public static function GetProfileId()
    {
        global $db;
        $LoggedUser = self::get_id();
        if (isset($_COOKIE['ProfileID']) && $_COOKIE['ProfileID'] != '') :
            $CookieProfileToken = StaticFunctions::clear($_COOKIE['ProfileID']);
            $CheckProfile = $db->query("SELECT * FROM profiles WHERE user_id='{$LoggedUser}' and profile_token = '{$CookieProfileToken}' and status=1  ")->fetch(PDO::FETCH_ASSOC);
            if ($CheckProfile) :
                return $CheckProfile['id'];
            endif;
        endif;

        $UserProfileCount = $db->query("SELECT id from profiles WHERE user_id='{$LoggedUser}' and status=1 ", PDO::FETCH_ASSOC)->rowCount();
        if ($UserProfileCount == 1) {
            $CheckProfile = $db->query("SELECT * FROM profiles WHERE user_id='{$LoggedUser}' and status=1  ")->fetch(PDO::FETCH_ASSOC);
            if ($CheckProfile) :
                self::new_session();
                $_SESSION['ProfileID'] = $CheckProfile['id'];
                $_SESSION['ProfileSession'] = (object) $CheckProfile;
                setcookie("ProfileID", $CheckProfile['profile_token'], time() + 604801, '/', DOMAIN, false, true);
                return $CheckProfile['id'];
            endif;
        }

        self::go('profiles');
        exit;
    }

    public static function MyDataQuery()
    {
        global $db;
        $MyProfileID = self::GetProfileId();
        $CheckUserData = $db->query("SELECT * FROM users_data WHERE user_id = '{$MyProfileID}'")->fetch(PDO::FETCH_ASSOC);

        if (!$CheckUserData) {
            $InsertData = $db->prepare("INSERT INTO users_data SET
        user_id = ?,
        my_list = ?,
        watch_list = ?");
            $insert = $InsertData->execute(array(
                $MyProfileID, json_encode([]), json_encode([])
            ));
            $CheckUserData = $db->query("SELECT * FROM users_data WHERE user_id = '{$MyProfileID}'")->fetch(PDO::FETCH_ASSOC);
        }

        return $CheckUserData;
    }

    public static function replace_tr($text)
    {
        $text = trim($text);
        $search = array('Ç', 'ç', 'Ğ', 'ğ', 'ı', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü', ' ');
        $replace = array('c', 'c', 'g', 'g', 'i', 'i', 'o', 'o', 's', 's', 'u', 'u', '-');
        $new_text = str_replace($search, $replace, $text);
        return $new_text;
    }

    public static function NoBarba()
    {
        if (isset($_SERVER['HTTP_X_BARBA'])) {
            http_response_code(401);
            exit;
        }
    }

    public static function LogOut($db)
    {
        self::new_session();
        if (isset($_SESSION['CheckSession'])) :
            $Me = self::get_id();
            $MyDevice = isset($_COOKIE['AppID']) ? self::clear($_COOKIE['AppID']) : null;
            $RememberToken = isset($_COOKIE['RMB']) ? self::clear($_COOKIE['RMB']) : null;

            if ($MyDevice != null) :
                $delete = $db->exec("DELETE FROM device_list WHERE user_id= '{$Me}' and device_token = '{$MyDevice}' ");
            endif;

            if ($RememberToken != null) :
                $delete = $db->exec("DELETE FROM remember_me WHERE user_id= '{$Me}' and remember_token = '{$RememberToken}' ");
                setcookie("RMB", null, -1, '/', DOMAIN, false, true);
                setcookie("ProfileID", null, -1, '/', DOMAIN, false, true);
                setcookie("AppID", null, -1, '/', DOMAIN, false, true);
            endif;
            session_destroy();
        endif;

        return null;
    }

    public static function BarbaLoaded($Css, $Js)
    {

        if (!isset($Js[0])) :
            $Js = [
                '/assets/netflux/js/null.js'
            ];
        endif;

        if (isset($_GET['__a']) && $_GET['__a'] == 1 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') :
            header('Content-Type: application/json');
            array_reverse($Js);
            array_reverse($Css);

            if (Debug == true) {
                foreach ($Css as $key => $value) {
                    $Css[$key] = $value . ((mb_strstr($value, '?')) ? '&t=' : '?t=') . time();
                }

                foreach ($Js as $key => $value) {
                    $Js[$key] = $value . ((mb_strstr($value, '?')) ? '&t=' : '?t=') . time();
                }
            }

            echo self::JsonOutput([
                'PageCss' => $Css,
                'PageJs' => $Js
            ]);
            exit;
        endif;
    }

    public static function reload_session()
    {
        global $db;
        StaticFunctions::new_session();
        $User = $_SESSION['UserSession']->id;
        // Reload session.
        return true;
    }

    public static function say($key)
    {
        return stripslashes($key);
    }

    public static function lang($key, $Ar = [])
    {

        if (file_exists(ROOT_DIR . '/assets/language.txt') && file_get_contents(ROOT_DIR . '/assets/language.txt') == 0) {
            return $key;
        }

        $Json = AppLanguage::LanguageJson();

        if (isset($Json[$key])) {
            $text = stripslashes($Json[$key]);
        } else {
            $text = stripslashes($key);
        }

        foreach ($Ar as $key => $value) {
            $text = str_replace('{' . $key . '}', $value, $text);
        }

        return $text;
    }

    public static function GenreTranslation($GenreName, $Translations)
    {
        if ($Translations == null || $Translations == '') {
            return $GenreName;
        }


        $Decode = json_decode($Translations, true);

        if (json_last_error() != 0) {
            return $GenreName;
        }

        if (isset($Decode[mb_strtolower(LANG)]['name'])) {
            return self::say($Decode[mb_strtolower(LANG)]['name']);
        } else {
            return $GenreName;
        }
    }

    public static function PacketTranslation($Packet, $Translations)
    {
        if ($Translations == null || $Translations == '') {
            return $Packet;
        }


        $Decode = json_decode($Translations, true);

        if (json_last_error() != 0) {
            return $Packet;
        }

        if (isset($Decode[mb_strtolower(LANG)]['name'])) {
            return self::say($Decode[mb_strtolower(LANG)]['name']);
        } else {
            return $Packet;
        }
    }

    public static function VideoTranslation($VideoName, $Translations, $Type)
    {
        if ($Translations == null || $Translations == '') {
            return $VideoName;
        }


        $Decode = json_decode($Translations, true);

        if (json_last_error() != 0) {
            return $VideoName;
        }

        if (isset($Decode[mb_strtolower(LANG)][$Type])) {
            return self::say($Decode[mb_strtolower(LANG)][$Type]);
        } else {
            return $VideoName;
        }
    }

    public static function random($get)
    {
        $token = bin2hex(openssl_random_pseudo_bytes($get));
        return $token;
    }

    public static function random_with_time($get)
    {
        $token = bin2hex(openssl_random_pseudo_bytes($get));
        $unix_time = time();
        $token2 = substr($token, 0, 20);
        $token3 = str_replace($token2, '', $token);
        $token = $token2 . $unix_time . $token3;
        return md5($token);
    }

    public static function timerFormat($start_time, $end_time, $std_format = false)
    {
        $total_time = $end_time - $start_time;
        $days       = floor($total_time / 86400);
        $hours      = floor($total_time / 3600);
        $minutes    = intval(($total_time / 60) % 60);
        $seconds    = intval($total_time % 60);
        $results = "";
        $Lang = [
            StaticFunctions::lang('4_day'),
            StaticFunctions::lang('5_hour'),
            StaticFunctions::lang('6_min'),
            StaticFunctions::lang('7_sec')
        ];
        if ($std_format == false) {
            if ($days > 0) {
                $results .= $days . (($days > 1) ? " " . $Lang[0] . " " : " " . $Lang[0] . " ");
            }
            if ($hours > 0) {
                $results .= $hours . (($hours > 1) ? " " . $Lang[1] . " " : " " . $Lang[1] . " ");
            }
            if ($minutes > 0) {
                $results .= $minutes . (($minutes > 1) ? " " . $Lang[2] . " " : " " . $Lang[2] . " ");
            }
            if ($seconds > 0) {
                $results .= $seconds . (($seconds > 1) ? " " . $Lang[3] . " " : " " . $Lang[3] . " ");
            }

            if ($seconds > 0) {
                $result = $seconds . ' ' . $Lang[3];
            }
            if ($minutes > 0) {
                $result = $minutes . ' ' . $Lang[2];
            }
            if ($hours > 0) {
                $result = $hours . ' ' . $Lang[1];
            }
            if ($days > 0) {
                $result = $days . ' ' . $Lang[0];
            }
        }
        if (!isset($result) || $result == '') {
            return '0 ' . $Lang[3];
        } else {
            return $result;
        }
    }

    public static function post($query)
    {
        if (isset($_POST[$query]) && StaticFunctions::clear($_POST[$query]) != '') {
            return StaticFunctions::clear($_POST[$query]);
        } else {
            return '';
        }
    }

    public static function getBrowser($agent = null)
    {
        $u_agent = ($agent != null) ? $agent : $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'Mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'Windows';
        }

        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
        }

        $i = count($matches['browser']);
        if ($i != 1) {
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform
        );
    }

    public static function View($View)
    {
        $View = mb_substr($View, 2);
        $ExplodeView = explode('.', $View);
        $RealView = $ExplodeView[0] . '/' . $View;
        return VDIR . '/' . $RealView;
    }


    public static function load_page($PageOptions)
    {

        $Page = $PageOptions['Class'];
        $FileName = $PageOptions['View'];
        $Params = $PageOptions['Params'];
        $Title = $PageOptions['Title'];

        if (!file_exists(VDIR . '/' . $Page . '/' . $Page . '.' . $FileName . '.php')) {
            require_once StaticFunctions::View('V' . '/page.404.php');
            exit;
        } else {
            $_Params = $Params;
            $__PageTitle = $Title . ' ' . PR_NAME;
            global $db;
            require_once StaticFunctions::View('V' . '/' . $Page . '.' . $FileName . '.php');
        }
    }

    public static function password($query)
    {
        $pass = sha1(base64_encode(md5(base64_encode($query))));
        $end = substr($pass, 5, 32);
        return $end;
    }

    public static function get_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        // For Localhost in development mode. (bypass ::1)
        if ($ipaddress == '::1' && Debug == true) {
            $ipaddress = '176.88.30.8';
        }

        return $ipaddress;
    }

    public static function JwtKey()
    {
        return 'E95d!AuLUvpm=@%x#K9p7ycc_=WMJrxbzCPZZQ@E95d!AuLUvpm=@%x#K9p7ycc_=WMJrxbzCPZZQ';
    }

    public static function AddLog($LogData, $Uid = '-')
    {
        global $db;

        if ($Uid == '-') {
            $Uid = StaticFunctions::get_id();
        }

        $CheckLog = $db->query("SELECT * FROM log WHERE user_id = " . $Uid . " and log_data='" . json_encode($LogData) . "' ")->fetch(PDO::FETCH_ASSOC);
        if (!$CheckLog) {
            $InsertLog = $db->prepare("INSERT INTO log SET
            user_id = :bir,
            log_data = :iki,
            log_time = :uc");
            $insert = $InsertLog->execute(array(
                "bir" => $Uid,
                "iki" => json_encode($LogData),
                "uc" => time()
            ));
        }

        return null;
    }

    public static function seo_link($text)
    {
        $text  = str_replace('&', '', $text);
        $find = array("/Ğ/", "/Ü/", "/Ş/", "/İ/", "/Ö/", "/Ç/", "/ğ/", "/ü/", "/ş/", "/ı/", "/ö/", "/ç/");
        $degis = array("G", "U", "S", "I", "O", "C", "g", "u", "s", "i", "o", "c");
        $text = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/", " ", $text);
        $text = preg_replace($find, $degis, $text);
        $text = preg_replace("/ +/", " ", $text);
        $text = preg_replace("/ /", "-", $text);
        $text = preg_replace("/\s/", "", $text);
        $text = strtolower($text);
        $text = preg_replace("/^-/", "", $text);
        $text = preg_replace("/-$/", "", $text);
        $text = str_replace('-amp-', '-', $text);
        return $text;
    }

    public static function validate_url($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);

        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
    }

    public static function fast_request($url)
    {
        $parts = parse_url($url);
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        $out = "GET " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Length: 0" . "\r\n";
        $out .= "Connection: Close\r\n\r\n";

        fwrite($fp, $out);
        fclose($fp);
    }
}