<?php

StaticFunctions::new_session();

if (isset($_SESSION['UserCurrency']['currency_code'])) {
    $UserCurrencyDecode = mb_strtoupper($_SESSION['UserCurrency']);
    $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='{$UserCurrencyDecode}' ")->fetch(PDO::FETCH_ASSOC);
    if ($UserCurrency) {
        define('UserCurrency', $UserCurrency);
    } else {
        $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='USD' ")->fetch(PDO::FETCH_ASSOC);
        define('UserCurrency', $UserCurrency);
    }
} else {

    $UserIp = StaticFunctions::get_ip();
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'http://www.geoplugin.net/json.gp?ip=' . $UserIp, [
        'http_errors' => false
    ]);

    $Body = $response->getBody();
    $Decode = json_decode($Body, true);

    if (isset($Decode['geoplugin_currencyCode']) && $Decode['geoplugin_currencyCode'] != '') {

        $UserCurrencyDecode = mb_strtoupper($Decode['geoplugin_currencyCode']);
        $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='{$UserCurrencyDecode}' ")->fetch(PDO::FETCH_ASSOC);
        if ($UserCurrency) {
            $_SESSION['UserCurrency'] = $UserCurrency['currency_code'];
            define('UserCurrency', $UserCurrency);
        } else {
            $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='USD' ")->fetch(PDO::FETCH_ASSOC);
            define('UserCurrency', $UserCurrency);
        }
    } else {
        $UserCurrency = $db->query("SELECT * from currencies WHERE currency_code='USD' ")->fetch(PDO::FETCH_ASSOC);
        define('UserCurrency', $UserCurrency);
    }
}