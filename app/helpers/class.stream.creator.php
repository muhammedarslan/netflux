<?php

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class NetfluxStreamCreator extends StaticFunctions
{

    protected $db;
    protected $VideoObject;

    public function setDb($DbConnect): void
    {
        $this->db = $DbConnect;
    }

    public function setVideo($Video): void
    {
        $this->VideoObject = $Video;
    }

    private function RemoteVideo($RandomName, $VideoLocation): void
    {
        $Client = new GuzzleHttp\Client();
        $Resource = fopen(APP_DIR . '/tmp/' . $RandomName . '.mp4', 'w');
        $Response = $Client->request('GET', $VideoLocation, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4408.5 Safari/537.36',
                'Cache-Control' => 'no-cache'
            ],
            'sink' => $Resource,
        ]);

        if (!mb_strstr($Response->getHeaderLine('content-type'), 'mp4')  ||  filesize(APP_DIR . '/tmp/' . $RandomName . '.mp4') < 5) {
            throw new Error('Uzak video dosyası alınırken bir sorun oluştu!');
        }
    }

    private function LocalVideo($RandomName, $VideoLocation): void
    {
        if (file_exists(ROOT_DIR . $VideoLocation)) {
            copy(ROOT_DIR . $VideoLocation, APP_DIR . '/tmp/' . $RandomName . '.mp4');
        } else {
            throw new Error('Yerel video dosyası bulunamadı!');
        }
    }

    private function VideoMp4TempLocation(): string
    {
        $VideoLocation = $this->VideoObject['video_source'];
        $RandomName = parent::random(32);

        if (filter_var($VideoLocation, FILTER_VALIDATE_URL)) {
            $this->RemoteVideo($RandomName, $VideoLocation);
        } else {
            $this->LocalVideo($RandomName, $VideoLocation);
        }

        return APP_DIR . '/tmp/' . $RandomName . '.mp4';
    }

    private function CreateStream($TmpVideo): array
    {

        return [];
    }

    public function StreamCreator(): void
    {
        $Mp4Location = $this->VideoMp4TempLocation();
        $CreateStream = $this->CreateStream($Mp4Location);
        print_r($CreateStream);
        exit;
    }
}