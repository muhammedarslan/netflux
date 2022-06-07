<?php

StaticFunctions::ajax_form('general');

$LangTo = StaticFunctions::post('hl');
$Path   = StaticFunctions::post('path');
$Url    = PROTOCOL . DOMAIN . $Path . '?hl=' . $LangTo;

$client = new GuzzleHttp\Client(['allow_redirects' => ['track_redirects' => true]]);
$response = $client->head($Url);
$redirects = $response->getHeader(GuzzleHttp\RedirectMiddleware::HISTORY_HEADER);
$effectiveUrl = end($redirects);

echo $effectiveUrl;