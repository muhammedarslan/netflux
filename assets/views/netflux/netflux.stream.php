<?php


$CheckVideo = $db->query("SELECT * FROM series_and_movies WHERE video_token = '{$token}' and video_source != '' ")->fetch(PDO::FETCH_ASSOC);
if (!$CheckVideo) {
    header("Location:" . PATH);
    exit;
}

$VideoMp4 = $CheckVideo['video_source'];

$VideoValid = false;
$client = new \GuzzleHttp\Client();
$response = $client->head($VideoMp4, [
    'http_errors' => false
]);
if ($response->getStatusCode() == 200) {
    if ($response->getHeaderLine('content-type') == 'video/mp4') {
        $VideoValid = true;
    }
}

if (!$VideoValid) {
    http_response_code(401);
    exit;
}

// $VideoMp4

$config = MuxPhp\Configuration::getDefaultConfiguration()
    ->setUsername(('278f7bd3-658a-4e7a-8f99-f8be54b90485'))
    ->setPassword(('zWqAB18LuMJk7nqN+UE3uw3j+fSWD+oKJdA3QvzC2fCoZrYVNWZAmAM4UOEiNolYZ5msTtG5QdO'));

// API Client Initialization
$assetsApi = new MuxPhp\Api\AssetsApi(
    new GuzzleHttp\Client(),
    $config
);

// Create Asset Request
$input = new MuxPhp\Models\InputSettings(["url" => $VideoMp4]);
$createAssetRequest = new MuxPhp\Models\CreateAssetRequest(["input" => $input, "playback_policy" => [MuxPhp\Models\PlaybackPolicy::PUBLIC_PLAYBACK_POLICY]]);

// Ingest
$result = $assetsApi->createAsset($createAssetRequest);

// Print URL
print "Playback URL: https://stream.mux.com/" . $result->getData()->getPlaybackIds()[0]->getId() . ".m3u8\n";