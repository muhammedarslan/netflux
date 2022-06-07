<?php


class NetfluxSocialLogin
{

    private $FacebookApp;

    private $GithubApp = [
        'AppID'     => '',
        'AppSecret' => ''
    ];

    private $GoogleApp = [
        'AppID'     => '',
        'AppSecret' => ''
    ];

    private $LinkedinApp = [
        'AppID'     => '',
        'AppSecret' => ''
    ];

    private $InstagramApp = [
        'AppID'     => '',
        'AppSecret' => ''
    ];

    public function go($with)
    {

        switch ($with) {
            case 'google':
                $this->LoginWithGoogle();
                break;
            case 'github':
                $this->LoginWithGithub();
                break;
            case 'linkedin':
                $this->LoginWithLinkedin();
                break;
            case 'facebook':
                $this->LoginWithFacebook();
                break;
            case 'instagram':
                $this->LoginWithInstagram();
                break;
            default:
                $this->Error404();
                break;
        }
    }

    private function Error404()
    {
        echo StaticFunctions::lang('18_something-went') . '<script>window.close();</script>';
        exit;
    }

    private function LoginWithGoogle()
    {
        $config = [
            'google' => [
                'client_id'     => $this->GoogleApp['AppID'],
                'client_secret' => $this->GoogleApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/google',
            ],
        ];

        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        $response = $socialite->driver('google')->redirect();
        echo $response->send();
        exit;
    }

    private function LoginWithGithub()
    {
        $config = [
            'github' => [
                'client_id'     => $this->GithubApp['AppID'],
                'client_secret' => $this->GithubApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/github',
            ],
        ];

        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        $response = $socialite->driver('github')->redirect();
        echo $response->send();
        exit;
    }

    private function LoginWithLinkedin()
    {
        $config = [
            'linkedin' => [
                'client_id'     => $this->LinkedinApp['AppID'],
                'client_secret' => $this->LinkedinApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/linkedin',
            ],
        ];

        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        $response = $socialite->driver('linkedin')->redirect();
        echo $response->send();
        exit;
    }

    private function LoginWithFacebook()
    {
        $config = [
            'facebook' => [
                'client_id'     => $this->FacebookApp['AppID'],
                'client_secret' => $this->FacebookApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/facebook',
            ],
        ];

        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        $response = $socialite->driver('facebook')->redirect();
        echo $response->send();
        exit;
    }

    private function LoginWithInstagram()
    {
        $config = [
            'apiKey'      => $this->InstagramApp['AppID'],
            'apiSecret'   => $this->InstagramApp['AppSecret'],
            'apiCallback' => 'https://' . DOMAIN . PATH . 'social-callback/instagram',
            'scope'       => ['user_profile'],
        ];

        header("Location:https://www.instagram.com/oauth/authorize?client_id=" . $this->InstagramApp['AppID'] . "&redirect_uri=" . urlencode($config['apiCallback']) . "&scope=user_profile&response_type=code");
        exit;
    }

    public function callback($with)
    {
        switch ($with) {
            case 'google':
                $UserArray = $this->CallbackGoogle();
                break;
            case 'github':
                $UserArray = $this->CallbackGithub();
                break;
            case 'linkedin':
                $UserArray = $this->CallbackLinkedin();
                break;
            case 'facebook':
                $UserArray = $this->CallbackFacebook();
                break;
            case 'instagram':
                $UserArray = $this->CallbackInstagram();
                break;
            default:
                $this->Error404();
                break;
        }
        $this->StartSession($UserArray);
    }

    private function CallbackGoogle()
    {
        $config = [
            'google' => [
                'client_id'     => $this->GoogleApp['AppID'],
                'client_secret' => $this->GoogleApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/google',
            ],
        ];
        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        try {
            $user = $socialite->driver('google')->user();
        } catch (\Throwable $th) {
            $this->Error404();
        }

        return [
            'Provider' => 'Google',
            'UserEmail' =>
            $user->getEmail(),
            'RealName' => $user->getName(),
            'Avatar' => $user->getAvatar()
        ];
    }

    private function CallbackGithub()
    {
        $config = [
            'github' => [
                'client_id'     => $this->GithubApp['AppID'],
                'client_secret' => $this->GithubApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/github',
            ],
        ];
        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        try {
            $user = $socialite->driver('github')->user();
        } catch (\Throwable $th) {
            $this->Error404();
        }

        return [
            'Provider' => 'Github',
            'UserEmail' =>
            $user->getEmail(),
            'RealName' => $user->getName(),
            'Avatar' => $user->getAvatar()
        ];
    }

    private function CallbackLinkedin()
    {
        $config = [
            'linkedin' => [
                'client_id'     => $this->LinkedinApp['AppID'],
                'client_secret' => $this->LinkedinApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/linkedin',
            ],
        ];
        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        try {
            $user = $socialite->driver('linkedin')->user();
        } catch (\Throwable $th) {
            $this->Error404();
        }

        return [
            'Provider' => 'Linkedin',
            'UserEmail' =>
            $user->getEmail(),
            'RealName' => $user->getName(),
            'Avatar' => $user->getAvatar()
        ];
    }

    private function CallbackFacebook()
    {
        $config = [
            'facebook' => [
                'client_id'     => $this->FacebookApp['AppID'],
                'client_secret' => $this->FacebookApp['AppSecret'],
                'redirect'      => PROTOCOL . DOMAIN . PATH . 'social-callback/facebook',
            ],
        ];
        $socialite = new Overtrue\Socialite\SocialiteManager($config);
        try {
            $user = $socialite->driver('facebook')->user();
        } catch (\Throwable $th) {
            $this->Error404();
        }

        return [
            'Provider' => 'Facebook',
            'UserEmail' =>
            $user->getEmail(),
            'RealName' => $user->getName(),
            'Avatar' => $user->getAvatar()
        ];
    }

    private function CallbackInstagram()
    {
        $config = [
            'apiKey'      => $this->InstagramApp['AppID'],
            'apiSecret'   => $this->InstagramApp['AppSecret'],
            'apiCallback' => 'https://' . DOMAIN . PATH . 'social-callback/instagram',
            'scope'       => ['user_profile'],
        ];

        $Rew = false;

        if (isset($_GET['code']) && $_GET['code'] != '') {

            $client = new \GuzzleHttp\Client();

            $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
                'http_errors' => false,
                'form_params' => [
                    'client_id' => $this->InstagramApp['AppID'],
                    'client_secret' => $this->InstagramApp['AppSecret'],
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $config['apiCallback'],
                    'code' => $_GET['code']
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $Json =  json_decode($response->getBody());

                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', 'https://graph.instagram.com/' . $Json->user_id . '?fields=id,username&access_token=' . $Json->access_token, [
                    'http_errors' => false
                ]);

                if ($response->getStatusCode() == 200) {
                    $UserJson = json_decode($response->getBody());
                    $Rew = true;
                    return [
                        'Provider' => 'Instagram',
                        'Username' => $UserJson->username,
                        'UserID' => $UserJson->id
                    ];
                }
            }
        }
        if ($Rew == false) {
            $this->Error404();
        }
    }

    private function StartSession($Array)
    {
        global $db;

        if ($Array['Provider'] == 'Instagram') {

            $UName = 'i_' . $Array['UserID'];
            $CheckUser = $db->query("SELECT * FROM users WHERE    username = '{$UName}'")->fetch(PDO::FETCH_ASSOC);
            if ($CheckUser) {
                if ($CheckUser['status'] == 1) {
                    $this->LoginID($CheckUser['id'], 'Instagram');
                }
            } else {
                $this->RegisterUser($Array);
            }
        } else {
            $Email = $Array['UserEmail'];
            $CheckUser = $db->query("SELECT * FROM users WHERE    email = '{$Email}'")->fetch(PDO::FETCH_ASSOC);
            if ($CheckUser) {
                if ($CheckUser['status'] == 1) {
                    $this->LoginID($CheckUser['id'], $Array['Provider']);
                }
            } else {
                $this->RegisterUser($Array);
            }
        }
        echo StaticFunctions::lang('3_you-are-being-redirected') . '<script>window.close();</script>';
        exit;
    }

    public function LoginID($Uid, $Source)
    {
        global $db;
        StaticFunctions::new_session();

        $UserQuery = $db->query('SELECT * FROM users WHERE id=' . $Uid . ' and status=1  ')->fetch(PDO::FETCH_ASSOC);
        if (!$UserQuery) {
            $this->Error404();
            exit;
        }
        $LastLoginUpdate = $db->prepare("UPDATE users SET
                     last_login   = :iki,
                    last_ip      = :uc,
                     last_type    = :lty
                     WHERE id = :dort");
        $update = $LastLoginUpdate->execute(array(
            'iki' => time(),
            'uc'  => StaticFunctions::get_ip(),
            "lty" => $Source,
            'dort' => $UserQuery['id']
        ));

        StaticFunctions::new_session();
        $_SESSION['CheckSession'] = 'active';
        $_SESSION['UserSession']    = (object) [
            'id' => $UserQuery['id'],
            'phone_code' => $UserQuery['phone_code'],
            'phone_number' => $UserQuery['phone_number'],
            'email' => $UserQuery['email'],
            'email_verify' => $UserQuery['email_verify'],
            'phone_verify' => $UserQuery['phone_verify'],
            'real_name' => $UserQuery['real_name'],
            'avatar' => $UserQuery['avatar'],
            'created_time' => $UserQuery['created_time'],
            'last_login' => $UserQuery['last_login'],
            'last_ip' => $UserQuery['last_ip'],
            'last_type' => $UserQuery['last_type'],
            'token' => $UserQuery['token']
        ];
        $_SESSION['UserID'] = $UserQuery['id'];

        StaticFunctions::AddLog(['Login' => [
            'UserId' => $UserQuery['id'],
            'UserIp' => StaticFunctions::get_ip(),
            'UserBrowser' => StaticFunctions::getBrowser(),
            'Type' => $Source
        ]]);

        $TwoStepProfile = (array) json_decode($UserQuery['2factor_profile']);
        $NextLevel = true;

        if (isset($TwoStepProfile['Profiles'][0]) && $TwoStepProfile['Profiles'][0] != '') {
            $_SESSION['SecureLevel_2Factor'] = true;
            $NextLevel = false;
        }

        $AuthLoginProfiles = (array) json_decode($UserQuery['authorized_login']);

        if ($AuthLoginProfiles[$Source] == false && $NextLevel == true) {
            $_SESSION['SecureLevel_Auth'] = true;
            $NextLevel = false;
        }

        if ($UserQuery['failed_login'] > 3 && $NextLevel == true) {
            $_SESSION['SecureLevel_FailedLogin'] = true;
            $NextLevel = false;
        }

        $payload = array(
            'UserId' => $UserQuery['id'],
            'UserIp' => StaticFunctions::get_ip(),
            'UserBrowser' => md5($_SERVER['HTTP_USER_AGENT'])
        );

        $jwt = \Firebase\JWT\JWT::encode($payload, StaticFunctions::JwtKey());
        $_SESSION['SecurityHash'] = $jwt;
        session_regenerate_id();

        return null;
    }

    private function RegisterUser($Array)
    {
        StaticFunctions::new_session();
        $_SESSION['RegisterEmail'] = $Array['UserEmail'];
        return null;
    }

    public function setFacebookApp($db)
    {
        $Settings = $db->query("SELECT facebook_app_id,facebook_app_secret FROM system_settings")->fetch(PDO::FETCH_ASSOC);
        $this->FacebookApp = [
            'AppID'     => $Settings['facebook_app_id'],
            'AppSecret' => $Settings['facebook_app_secret']
        ];
    }
}