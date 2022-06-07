<?php


StaticFunctions::ajax_form('admin');
StaticFunctions::new_session();
$ID = StaticFunctions::post('video_id');

$GetData = $db->query("SELECT * FROM series_and_movies WHERE id = '{$ID}'")->fetch(PDO::FETCH_ASSOC);

$Video  = $GetData['video_source'];
$Stream = $GetData['video_stream_url'];

if (strstr($Video, '.m3u8')) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('34_the-video-file-already-looks-like-a'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}

$VideoValid = false;

if (filter_var($Video, FILTER_VALIDATE_URL)) {
    $client = new \GuzzleHttp\Client();
    $response = $client->head($Video, [
        'http_errors' => false
    ]);
    if ($response->getStatusCode() == 200) {
        if ($response->getHeaderLine('content-type') == 'video/mp4') {
            $VideoValid = true;
        }
    }
}

if (!$VideoValid) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('35_its-source-file-does-not-look-like-a'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}


try {

    $M3U8DirName = StaticFunctions::random(32);
    mkdir(ROOT_DIR . '/assets/stream/' . $M3U8DirName, 0755);
    $ShellCode = 'ffmpeg -i "' . $Video . '" -codec: copy -start_number 0 -hls_time 10 -hls_list_size 0 -f hls ' . ROOT_DIR . '/assets/stream/' . $M3U8DirName . '/stream.m3u8';
    $Shell = shell_exec($ShellCode . " </dev/null >/dev/null 2>&1");

    sleep(1);
    if (file_exists(ROOT_DIR . '/assets/stream/' . $M3U8DirName . '/stream.m3u8')) {
        $StreamLocation = PATH . 'assets/stream/' . $M3U8DirName . '/stream.m3u8';

        $UpdateStream = $db->prepare("UPDATE series_and_movies SET
        video_stream_url = :streamurl
        WHERE id = :sids");
        $update = $UpdateStream->execute(array(
            "streamurl" => $StreamLocation,
            "sids" => $GetData['id']
        ));

        echo StaticFunctions::JsonOutput([
            'label' => 'success',
            'text' => StaticFunctions::lang('36_the-post-has-been-successfully-created'),
            'clearInput' => false,
            'closeModal' => true,
            'refreshTable' => false
        ]);
        exit;
    } else {
        echo StaticFunctions::JsonOutput([
            'label' => 'error',
            'text' => StaticFunctions::lang('37_there-was-a-problem-processing-the'),
            'clearInput' => false,
            'closeModal' => false,
            'refreshTable' => false
        ]);
        exit;
    }
} catch (\Throwable $th) {
    echo StaticFunctions::JsonOutput([
        'label' => 'error',
        'text' => StaticFunctions::lang('37_there-was-a-problem-processing-the'),
        'clearInput' => false,
        'closeModal' => false,
        'refreshTable' => false
    ]);
    exit;
}