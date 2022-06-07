<?php

class NetfluxSessionManager
{

    private $LoggedUser;
    private $DatabaseConnection;
    private $MaxSessionTime = (60 * 10);
    public  $IsCli = false;

    private function DeviceId()
    {
        if (isset($_COOKIE['AppID']) && $_COOKIE['AppID'] != '') :
            $TempDeviceID = StaticFunctions::clear($_COOKIE['AppID']);
            $LoggedUser = $this->LoggedUser['id'];
            $db = $this->DatabaseConnection;
            $Activity = time() - $this->MaxSessionTime;
            $CheckDevice = $db->query("SELECT * FROM device_list WHERE user_id='{$LoggedUser}' and device_token = '{$TempDeviceID}' and last_activity > $Activity  ")->fetch(PDO::FETCH_ASSOC);
            if ($CheckDevice) :

                if ($CheckDevice['status'] != 1) {
                    $DeleteDevice = $db->prepare("DELETE FROM device_list WHERE id = :id");
                    $delete = $DeleteDevice->execute(array(
                        'id' => $CheckDevice['id']
                    ));
                    StaticFunctions::LogOut($db);
                    StaticFunctions::go(LANG . '/login');
                    exit;
                }

                return $CheckDevice['device_token'];
            else :
                return null;
            endif;
        else :
            return null;
        endif;
    }

    private function ActiveDeviceList()
    {
        $LoggedUser = $this->LoggedUser['id'];
        $db = $this->DatabaseConnection;
        $Activity = time() - $this->MaxSessionTime;
        $DevicesArray = [
            'Count' => 0,
            'DeviceList' => []
        ];

        $GetActiveDevices = $db->query("SELECT * FROM device_list WHERE user_id='{$LoggedUser}' and last_activity > $Activity and status=1 ORDER BY last_activity DESC ", PDO::FETCH_ASSOC);
        if ($GetActiveDevices->rowCount()) {
            foreach ($GetActiveDevices as $row) {
                $DevicesArray['Count']++;
                array_push($DevicesArray['DeviceList'], $row['device_token']);
            }
        }

        return $DevicesArray;
    }

    private function MaxSessionCount()
    {
        $db = $this->DatabaseConnection;
        $UserPacket = $this->LoggedUser['user_packet'];
        $GetPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacket}'")->fetch(PDO::FETCH_ASSOC);

        if ( $this->LoggedUser['user_type'] == 'admin' ) return 999;

        if ($GetPacket) :
            return $GetPacket['max_session_count'];
        else :
            return 2;
        endif;
    }

    public function setUser($UserQuery)
    {
        $this->LoggedUser = $UserQuery;
    }

    public function setDb($db)
    {
        $this->DatabaseConnection = $db;
    }

    public function setCli()
    {
        $this->IsCli = true;
    }

    private function GetPage()
    {
        $route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
        $route_method = $_SERVER['REQUEST_METHOD'];
        $route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
        $route_path = AppLanguage::UrlMaker($route_path);
        return $route_path;
    }

    private function SessionExceed()
    {
        if ($this->IsCli == true) {
            return false;
        } else {
            if ($this->GetPage() != '/log-out') :
                $PageOptions = [
                    'Title'  => StaticFunctions::lang('12_maximum-number-of-devices'),
                    'Params' => [],
                    'View'   => 'session.exceed',
                    'Class'  => 'netflux',
                    'BodyE'  => null
                ];
                StaticFunctions::load_page($PageOptions);
                exit;
            endif;
        }
    }

    private function DeviceProperties()
    {
        $browser = new Sinergi\BrowserDetector\Browser();
        $os = new Sinergi\BrowserDetector\Os();
        $device = new Sinergi\BrowserDetector\Device();
        $language = new Sinergi\BrowserDetector\Language();

        return json_encode([
            'Browser' => $browser->getName(),
            'Os' => $os->getName(),
            'Device' => $device->getName(),
            'Language' => $language->getLanguage()
        ]);
    }

    private function RegisterDevice()
    {
        $RandomToken = StaticFunctions::random(64);
        $db = $this->DatabaseConnection;
        $LoggedUser = $this->LoggedUser['id'];
        $Device = $this->DeviceProperties();
        $InsertDevice = $db->prepare("INSERT INTO device_list SET
            user_id = ?,
            device_token = ?,
            device_properties = ?,
            last_activity = ?");
        $insert = $InsertDevice->execute(array(
            $LoggedUser, $RandomToken, $Device, time()
        ));
        setcookie("AppID", $RandomToken, time() + 604801, '/', DOMAIN, false, true);

        return true;
    }

    public function VerifySession()
    {
        $MaxSession    =  $this->MaxSessionCount();
        $DeviceID      =  $this->DeviceId();
        $ActiveDevices =  $this->ActiveDeviceList();

        $CanAccess = false;

        if ($MaxSession > $ActiveDevices['Count']) {
            $CanAccess = true;
            if ($DeviceID == null) $this->RegisterDevice();
        }

        if ($DeviceID != null) {
            if (in_array($DeviceID, $ActiveDevices['DeviceList'])) {
                $CanAccess = true;
            }
        }

        if ($CanAccess == true) {
            return ($this->IsCli == true) ? true : null;
        } else {
            $this->SessionExceed();
            return false;
        }
    }
}