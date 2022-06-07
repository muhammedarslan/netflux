<?php

class ProfileManager
{

    private $LoggedUser;
    private $DatabaseConnection;
    public  $ProfileLanguage = LANG;

    private function ProfileID()
    {
        if (isset($_COOKIE['ProfileID']) && $_COOKIE['ProfileID'] != '') :
            $CookieProfileToken = StaticFunctions::clear($_COOKIE['ProfileID']);
            $LoggedUser = $this->LoggedUser['id'];
            $db = $this->DatabaseConnection;
            $CheckProfile = $db->query("SELECT * FROM profiles WHERE user_id='{$LoggedUser}' and profile_token = '{$CookieProfileToken}' and status=1  ")->fetch(PDO::FETCH_ASSOC);
            if ($CheckProfile) :
                return $CheckProfile['profile_token'];
            else :
                return null;
            endif;
        else :
            return null;
        endif;
    }

    private function MaxProfileCount()
    {
        $db = $this->DatabaseConnection;
        $UserPacket = $this->LoggedUser['user_packet'];
        $GetPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacket}'")->fetch(PDO::FETCH_ASSOC);

        if ($GetPacket) :
            return $GetPacket['max_profile_count'];
        else :
            return 2;
        endif;
    }

    private function ProfileCount()
    {
        $LoggedUser = $this->LoggedUser['id'];
        $db = $this->DatabaseConnection;
        $ProfileCount = 0;

        $GetActiveDevices = $db->query("SELECT * FROM profiles WHERE user_id='{$LoggedUser}' and status=1 ", PDO::FETCH_ASSOC);
        if ($GetActiveDevices->rowCount()) {
            foreach ($GetActiveDevices as $row) {
                $ProfileCount++;
            }
        }

        return $ProfileCount;
    }

    public function setUser($UserQuery)
    {
        $this->LoggedUser = $UserQuery;
    }

    public function setDb($db)
    {
        $this->DatabaseConnection = $db;
    }

    private function CreateFirstProfile()
    {
        $User = $this->LoggedUser;
        $db   = $this->DatabaseConnection;

        $InsertProfile = $db->prepare("INSERT INTO profiles SET
            user_id = ?,
            profile_name = ?,
            profile_avatar = ?,
            profile_token = ?,
            created_time = ?,
            status = ?");
        $insert = $InsertProfile->execute(array(
            $User['id'], $User['real_name'], $User['avatar'], StaticFunctions::random(32), time(), 1
        ));

        return true;
    }

    private function getSingleProfile()
    {
        $LoggedUser = $this->LoggedUser['id'];
        $db = $this->DatabaseConnection;
        $CheckProfile = $db->query("SELECT * FROM profiles WHERE user_id='{$LoggedUser}' and status= 1  ")->fetch(PDO::FETCH_ASSOC);
        if ($CheckProfile) :
            return $CheckProfile['profile_token'];
        else :
            return null;
        endif;
    }

    public function CheckProfileToken($ProfileToken)
    {
        $ProfileToken = StaticFunctions::clear($ProfileToken);
        $LoggedUser = StaticFunctions::get_id();
        $db = $this->DatabaseConnection;
        $CheckProfile = $db->query("SELECT * FROM profiles WHERE profile_token='{$ProfileToken}' and user_id='{$LoggedUser}' and status= 1   ")->fetch(PDO::FETCH_ASSOC);
        if ($CheckProfile) :
            return true;
        else :
            return false;
        endif;
    }

    public function set($ProfileToken)
    {
        $LoggedUser = StaticFunctions::get_id();
        $db = $this->DatabaseConnection;
        $CheckProfile = $db->query("SELECT * FROM profiles WHERE profile_token='{$ProfileToken}' and user_id='{$LoggedUser}' and status= 1   ")->fetch(PDO::FETCH_ASSOC);
        if ($CheckProfile) :
            StaticFunctions::new_session();
            $_SESSION['ProfileID'] = $CheckProfile['id'];
            $_SESSION['ProfileSession'] = (object) $CheckProfile;
            setcookie("ProfileID", $CheckProfile['profile_token'], time() + 604801, '/', DOMAIN, false, true);
            $this->ProfileLanguage = mb_strtolower($CheckProfile['profile_language']);

            /*
            if (mb_strtolower(LANG) != mb_strtolower($CheckProfile['profile_language'])) {
                $ChangeLang = mb_strtolower($CheckProfile['profile_language']);
                setcookie('AppLang', $ChangeLang, time() + 60 * 60 * 24 * 30, '/');
            }
            */

            return null;
        else :
            StaticFunctions::LogOut($db);
            StaticFunctions::go_home();
        endif;
    }

    private function GetPage()
    {
        $route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
        $route_method = $_SERVER['REQUEST_METHOD'];
        $route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
        $route_path = AppLanguage::UrlMaker($route_path);
        return $route_path;
    }

    public function ProfileLang()
    {
        return $this->ProfileLanguage;
    }

    public function setProfile()
    {
        $User         = $this->LoggedUser;
        $db           = $this->DatabaseConnection;
        $MaxProfile   = $this->MaxProfileCount();
        $ProfileCount = $this->ProfileCount();
        $ProfileID    = $this->ProfileID();
        $PageExplode  = explode('/', $this->GetPage());

        if (@$PageExplode[1] != 'account') {
            if ($ProfileCount == 0) {
                $this->CreateFirstProfile();
                $ProfileCount = $this->ProfileCount();
            }

            if ($ProfileID != null) :
                $this->set($ProfileID);
            else :
                if ($ProfileCount == 1) :
                    $SingleProfileID = $this->getSingleProfile();
                    $this->set($SingleProfileID);
                else :
                    if (isset($_GET['switch']) && $_GET['switch'] != '') :
                        if ($this->CheckProfileToken($_GET['switch'])) :
                            $this->set($_GET['switch']);
                            StaticFunctions::go('browse?hl=' . $this->ProfileLang());
                            exit;
                        endif;
                    endif;
                    require_once StaticFunctions::View('V' . '/profile.selector.php');
                    exit;
                endif;
            endif;
        }
    }
}