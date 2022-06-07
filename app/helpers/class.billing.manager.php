<?php

class NetfluxBilling
{

    private $LoggedUser;
    private $DatabaseConnection;
    private $BillingMode;
    private $PaypalApiUrl = [
        'Live' => 'https://api.paypal.com',
        'Sandbox' => 'https://api.sandbox.paypal.com'
    ];

    public function verifyBilling()
    {
        if ($this->GetPage() != '/account/packets' && $this->GetPage() != '/account') :
            if (!$this->CheckFreePacket()) :
                $db = $this->DatabaseConnection;
                $UserID = $this->userID();
                $UserQuery = $this->LoggedUser;
                $CurrentTime = time();
                $CheckUserBilling = $db->query("SELECT * FROM payments WHERE user_id = '{$UserID}' and payment_finish_time > $CurrentTime ")->fetch(PDO::FETCH_ASSOC);
                if (!$CheckUserBilling) {
                    $UserPacketID = $UserQuery['user_packet'];
                    $UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacketID}'")->fetch(PDO::FETCH_ASSOC);
                    if ($UserPacket['packet_price'] > 0) {
                        if ($UserPacket['trial_period'] > 0) {
                            $CheckUserTrial = $db->query("SELECT * FROM payments WHERE user_id = '{$UserID}' and payment_type='trial' ")->fetch(PDO::FETCH_ASSOC);
                            if (!$CheckUserTrial) {
                                $this->TrialVersion();
                                exit;
                            }
                        }

                        $CheckNewUser = $db->query("SELECT id from profiles WHERE user_id='{$UserID}'")->fetch(PDO::FETCH_ASSOC);
                        if ($CheckNewUser) {
                            $this->PaypalSubscriber($UserID);
                            $this->StripeSubscriber($UserID);
                        }
                        $this->PaymentRequired();
                    }
                }
            endif;
        endif;
    }

    public function verifyBillingCli()
    {
        $ReturnObject = false;
        if (!$this->CheckFreePacket()) :
            $db = $this->DatabaseConnection;
            $UserID = $this->userID();
            $UserQuery = $this->LoggedUser;
            $CurrentTime = time();
            $CheckUserBilling = $db->query("SELECT * FROM payments WHERE user_id = '{$UserID}' and payment_finish_time > $CurrentTime ")->fetch(PDO::FETCH_ASSOC);
            if (!$CheckUserBilling) {
                $UserPacketID = $UserQuery['user_packet'];
                $UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacketID}'")->fetch(PDO::FETCH_ASSOC);
                if ($UserPacket['packet_price'] > 0) {
                    if ($UserPacket['trial_period'] > 0) {
                        $CheckUserTrial = $db->query("SELECT * FROM payments WHERE user_id = '{$UserID}' and payment_type='trial' ")->fetch(PDO::FETCH_ASSOC);
                    }
                    $ReturnObject = true;
                }
            }
        endif;

        return $ReturnObject;
    }

    public function PaypalSubscriber($UserID)
    {
        require_once CORE_DIR . '/payments/subscriber.paypal.php';
    }

    public function StripeSubscriber($UserID)
    {
        require_once CORE_DIR . '/payments/subscriber.stripe.php';
    }

    public function PaypalAuth()
    {
        $ApiUrl = $this->GetApiUrl('paypal');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $ApiUrl . '/v1/oauth2/token', [
            'http_errors' => false,
            'auth' => [$this->PaypalApi()['ClientID'], $this->PaypalApi()['Secret']],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept-Language' => 'en_US',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            http_response_code(401);
            exit;
        }

        $JsonResponse =  json_decode($response->getBody());
        return $JsonResponse->access_token;
    }

    public function CancelPaypalSub($OldSubID)
    {
        try {
            require_once CORE_DIR . '/payments/cancel.paypal.php';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function CancelStripeSub($OldSubID)
    {
        try {
            require_once CORE_DIR . '/payments/cancel.stripe.php';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function PaypalClientID()
    {
        return $this->PaypalApi()['ClientID'];
    }

    public function PaypalApi()
    {
        $db = $this->DatabaseConnection;
        $Set = $db->query("SELECT * FROM system_settings")->fetch(PDO::FETCH_ASSOC);

        return [
            'ClientID' => $Set['paypal_id'],
            'Secret'   => $Set['paypal_secret']
        ];
    }

    public function StripeApi()
    {
        $db = $this->DatabaseConnection;
        $Set = $db->query("SELECT * FROM system_settings")->fetch(PDO::FETCH_ASSOC);

        return [
            'ClientID' => $Set['stripe_id'],
            'Secret'   => $Set['stripe_secret']
        ];
    }

    public function PaypalCreatePlan($Name, $Price)
    {
        $db = $this->DatabaseConnection;
        $ApiUrl = $this->GetApiUrl('paypal');
        $BearerToken = $this->PaypalAuth();

        $GetPaypalProduct = $db->query("SELECT * FROM system_settings WHERE id =1 ")->fetch(PDO::FETCH_ASSOC);
        if (!$GetPaypalProduct) {
            http_response_code(401);
            exit;
        }


        if ($GetPaypalProduct['paypal_product_id'] == '') {

            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $ApiUrl . '/v1/catalogs/products', [
                'http_errors' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Accept-Language' => 'en_US',
                    'Authorization' => 'Bearer ' . $BearerToken
                ],
                'body' => StaticFunctions::ApiJson(
                    [
                        'name' => 'Netflux Video Streaming Service',
                        'type' => 'SERVICE',
                        'category' => 'DIGITAL_MEDIA_BOOKS_MOVIES_MUSIC',
                    ]
                )
            ]);

            if ($response->getStatusCode() != 201) {
                http_response_code(401);
                exit;
            }

            $JsonResponse =  json_decode($response->getBody(), true);
            $ProductID = $JsonResponse['id'];

            $query = $db->prepare("UPDATE system_settings SET
        paypal_product_id = :nid");
            $update = $query->execute(array(
                "nid" => $ProductID
            ));
        } else {
            $ProductID = $GetPaypalProduct['paypal_product_id'];
        }

        $ReturnArray = [];
        $BasePrice = $Price;
        $UsPlan = '';

        $GetCurrencies = $db->query("SELECT * from currencies ", PDO::FETCH_ASSOC);
        if ($GetCurrencies->rowCount()) {
            foreach ($GetCurrencies as $key => $row) {
                $RowPrice = StaticFunctions::FloatPrice($BasePrice * $row['exchange_rate'], $row['rounding_type']);
                $SendCurrencyCode = $row['currency_code'];

                if ($row['currency_code'] == 'TRY') {
                    $SendCurrencyCode = 'USD';

                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD', [
                        'http_errors' => false
                    ]);

                    $Body = $response->getBody();
                    $DecodeTRYJson = json_decode($Body, true)['rates'];
                    $RowPrice = number_format($RowPrice / $DecodeTRYJson['TRY'], 2);
                }

                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', $ApiUrl . '/v1/billing/plans', [
                    'http_errors' => false,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Accept-Language' => 'en_US',
                        'Authorization' => 'Bearer ' . $BearerToken
                    ],
                    'body' => '{
  "product_id": "' . $ProductID . '",
  "name": "' . $Name . '",
  "description": "Video Streaming Service ' . $Name . ' plan",
  "status": "ACTIVE",
  "billing_cycles": [
    {
      "frequency": {
        "interval_unit": "MONTH",
        "interval_count": 1
      },
      "tenure_type": "REGULAR",
      "sequence": 1,
      "total_cycles": 12,
      "pricing_scheme": {
        "fixed_price": {
          "value": "' . $RowPrice . '",
          "currency_code": "' . $SendCurrencyCode . '"
        }
      }
    }
  ],
  "payment_preferences": {
    "payment_failure_threshold": 0
  }
}'
                ]);

                if ($response->getStatusCode() != 201) {
                    $ReturnArray[$row['currency_code']] = $UsPlan;
                    continue;
                }
                $JsonResponse =  json_decode($response->getBody(), true);
                $PlanID = $JsonResponse['id'];
                if ($UsPlan == '') $UsPlan = $PlanID;
                $ReturnArray[$row['currency_code']] = $PlanID;
            }
        }


        $ReturnPacketsIds =  json_encode($ReturnArray);
        return $ReturnPacketsIds;
    }

    public function StripeCreatePlan($Name, $Price)
    {
        $db = $this->DatabaseConnection;
        $StripeApiInfo = $this->StripeApi();

        $GetStripeProduct = $db->query("SELECT * FROM system_settings WHERE id =1 ")->fetch(PDO::FETCH_ASSOC);
        if (!$GetStripeProduct) {
            http_response_code(401);
            exit;
        }


        if ($GetStripeProduct['stripe_product_id'] == '') {

            $stripe = new \Stripe\StripeClient(
                $StripeApiInfo['Secret']
            );
            $response = $stripe->products->create([
                'name' => 'Netflux Video Streaming',
            ]);

            $ProductID = $response->id;

            $query = $db->prepare("UPDATE system_settings SET
        stripe_product_id = :nid");
            $update = $query->execute(array(
                "nid" => $ProductID
            ));
        } else {
            $ProductID = $GetStripeProduct['stripe_product_id'];
        }

        $ReturnArray = [];
        $BasePrice = $Price;

        $GetCurrencies = $db->query("SELECT * from currencies ", PDO::FETCH_ASSOC);
        if ($GetCurrencies->rowCount()) {
            foreach ($GetCurrencies as $key => $row) {
                $RowPrice = StaticFunctions::FloatPrice($BasePrice * $row['exchange_rate'], $row['rounding_type']);
                $stripe = new \Stripe\StripeClient(
                    $StripeApiInfo['Secret']
                );
                $response = $stripe->prices->create([
                    'unit_amount' => ($RowPrice * 100),
                    'currency' => mb_strtolower($row['currency_code']),
                    'recurring' => ['interval' => 'month'],
                    'product' => $ProductID
                ]);

                $PlanID = $response->id;
                $ReturnArray[$row['currency_code']] = $PlanID;
            }
        }


        $ReturnPacketsIds =  json_encode($ReturnArray);
        return $ReturnPacketsIds;
    }

    public function StripePlanID($PlanID)
    {
        $db = $this->DatabaseConnection;
        $GetPlan = $db->query("SELECT * FROM packets WHERE id = '{$PlanID}'")->fetch(PDO::FETCH_ASSOC);
        $StripeJson = json_decode($GetPlan['stripe_packet_id'], true);
        $StripeID = (isset($StripeJson[UserCurrency['currency_code']])) ? $StripeJson[UserCurrency['currency_code']] : '---';

        try {
            $stripe = new \Stripe\StripeClient(
                $this->StripeApi()['Secret']
            );
            $stripe->prices->retrieve(
                $StripeID,
                []
            );

            return $StripeID;
        } catch (\Throwable $th) {
            $NPIDJson = $this->StripeCreatePlan($GetPlan['packet_name'], $GetPlan['packet_price']);
            $StripeJson = json_decode($NPIDJson, true);
            $NPID = $StripeJson[UserCurrency['currency_code']];

            $UpdatePriceID = $db->prepare("UPDATE packets SET
            stripe_packet_id = :n
            WHERE id = :i");
            $update = $UpdatePriceID->execute(array(
                "n" => $NPIDJson,
                "i" => $GetPlan['id']
            ));

            return $NPID;
        }
    }

    public function PaypalPlanID($PlanID)
    {
        $db = $this->DatabaseConnection;
        $ApiUrl = $this->GetApiUrl('paypal');
        $BearerToken = $this->PaypalAuth();
        $GetPlan = $db->query("SELECT * FROM packets WHERE id = '{$PlanID}'")->fetch(PDO::FETCH_ASSOC);
        $Paypaljson = json_decode($GetPlan['paypal_packet_id'], true);
        $PaypalID = (isset($Paypaljson[UserCurrency['currency_code']])) ? $Paypaljson[UserCurrency['currency_code']] : '---';

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $ApiUrl . '/v1/billing/plans/' . $PaypalID, [
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Accept-Language' => 'en_US',
                'Authorization' => 'Bearer ' . $BearerToken
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            return $PaypalID;
        } else {
            $NPID = $this->PaypalCreatePlan($GetPlan['packet_name'], $GetPlan['packet_price']);

            $UpdatePriceID = $db->prepare("UPDATE packets SET
            paypal_packet_id = :n
            WHERE id = :i");
            $update = $UpdatePriceID->execute(array(
                "n" => $NPID,
                "i" => $GetPlan['id']
            ));

            return json_decode($NPID, true)[UserCurrency['currency_code']];
        }
    }

    private function PaymentRequired()
    {
        if (!$this->CheckFreePacket()) :
            $db = $this->DatabaseConnection;
            $UserID = $this->userID();
            $UserQuery = $this->LoggedUser;
            $CurrentTime = time();
            $CheckUserBilling = $db->query("SELECT * FROM payments WHERE user_id = '{$UserID}' and payment_finish_time > $CurrentTime ")->fetch(PDO::FETCH_ASSOC);
            if (!$CheckUserBilling) {
                $UserPacketID = $UserQuery['user_packet'];
                $UserPacket = $db->query("SELECT * FROM packets WHERE id = '{$UserPacketID}'")->fetch(PDO::FETCH_ASSOC);
                if ($UserPacket['trial_period'] > 0) {
                    $CheckUserTrial = $db->query("SELECT * FROM payments WHERE user_id = '{$UserID}' and payment_type='trial' ")->fetch(PDO::FETCH_ASSOC);
                    if (!$CheckUserTrial) {
                        $this->TrialVersion();
                        exit;
                    }
                }
                $PageOptions = [
                    'Title'  => StaticFunctions::lang('1_netflux'),
                    'Params' => [],
                    'View'   => 'required',
                    'Class'  => 'payment',
                    'BodyE'  => null
                ];
                StaticFunctions::load_page($PageOptions);
                exit;
            }
        endif;
    }

    private function CheckFreePacket()
    {
        $db = $this->DatabaseConnection;
        $UserID = $this->userID();
        $MyPacket = $db->query("SELECT * FROM packets INNER JOIN users on packets.id=users.user_packet WHERE users.id='{$UserID}'  ")->fetch(PDO::FETCH_ASSOC);
        if ($MyPacket['packet_price'] > 0) {
            return false;
        } else {
            return true;
        }
    }

    private function TrialVersion()
    {
        $PageOptions = [
            'Title'  => StaticFunctions::lang('2_welcome'),
            'Params' => [],
            'View'   => 'page',
            'Class'  => 'welcome',
            'BodyE'  => null
        ];
        StaticFunctions::load_page($PageOptions);
        exit;
    }

    public function GetApiUrl($Type)
    {
        switch ($Type) {
            case 'paypal':
                if ($this->BillingMode == 'live') :
                    return $this->PaypalApiUrl['Live'];
                else :
                    return $this->PaypalApiUrl['Sandbox'];
                endif;
                break;

            default:
                return null;
                break;
        }
    }

    private function GetPage()
    {
        $route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
        $route_method = $_SERVER['REQUEST_METHOD'];
        $route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
        $route_path = AppLanguage::UrlMaker($route_path);
        return $route_path;
    }

    private function userID()
    {
        return $this->LoggedUser['id'];
    }

    public function setUser($UserQuery)
    {
        $this->LoggedUser = $UserQuery;
    }

    public function setDb($db)
    {
        $this->DatabaseConnection = $db;
        $Set = $db->query("SELECT * FROM system_settings")->fetch(PDO::FETCH_ASSOC);
        $this->BillingMode = $Set['app_mode'];
    }
}