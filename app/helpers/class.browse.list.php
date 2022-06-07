<?php

class NetfluxList extends StaticFunctions
{

    protected $db;
    protected $profileList;
    protected $profileWatched;
    protected $rows;

    public function setDb($Database): void
    {
        $this->db = $Database;
    }

    public function setProfileList($List): void
    {
        $this->profileList = $List;
    }

    public function setProfileWatched($List): void
    {
        $this->profileWatched = $List;
    }

    public function setRows($rows): void
    {
        $this->rows = $rows;
    }

    private function videoCategories($CategoriesArray): array
    {
        $CategoryIDs = json_decode($CategoriesArray, true);
        $ImpLode = implode(',', $CategoryIDs);
        $Categories = [];
        $CategoriesQuery = $this->db->query("SELECT * FROM genres WHERE id IN($ImpLode) Order by Rand() LIMIT 3  ", PDO::FETCH_ASSOC);
        if ($CategoriesQuery->rowCount()) {
            foreach ($CategoriesQuery as $row) {
                array_push($Categories, parent::GenreTranslation($row['genres_name'], $row['genre_translations']));
            }
        }
        return $Categories;
    }

    private function videoImages($Images): array
    {
        $ImagesDecode = json_decode($Images, true);
        $ImagesDecode =  array_values($ImagesDecode);
        $Logo = $ImagesDecode[0];
        unset($ImagesDecode[0]);

        if (count($ImagesDecode) == 0) {
            array_push($ImagesDecode, $Logo);
        }

        $ImagesDecode = array_values($ImagesDecode);
        return [
            'videoLogo' => $Logo,
            'videoImages' => $ImagesDecode
        ];
    }

    private function inProfileList($itemID): bool
    {
        return (in_array($itemID, $this->profileList)) ? true : false;
    }

    private function AdulthoodLevel($Level): string
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

    private function contentLevelControl($videoLevel): bool
    {
        $ProfileID = parent::GetProfileId();
        $ProfileLevel = $this->db->query("SELECT profile_level from profiles WHERE id='{$ProfileID}' ")->fetch(PDO::FETCH_ASSOC)['profile_level'];
        if ($ProfileLevel > $videoLevel) {
            return false;
        } else {
            return true;
        }
    }

    private function matchScore($Item): string
    {
        return '99% ' . parent::lang('66_matching');
    }

    private function watchedDuration($VideoDuration, $VideoID): float
    {
        if ($VideoDuration < 1) return 0;

        if (isset($this->profileWatched[$VideoID])) {
            $WatchedDuration = ceil($this->profileWatched[$VideoID]);
            $WatchedPercent = ceil($WatchedDuration / $VideoDuration * 100);
            if ($WatchedPercent < 5) $WatchedPercent = 5;
            return $WatchedPercent;
        } else {
            return 0;
        }
    }

    private function userLikedList(): array
    {
        $userList = parent::MyDataQuery();
        return [
            'userLiked' => json_decode($userList['my_liked'], true),
            'userUnliked' => json_decode($userList['my_unliked'], true),
        ];
    }

    private function userLiked($VideoID): bool
    {
        return (in_array($VideoID, $this->userLikedList()['userLiked'])) ? true : false;
    }

    private function userUnliked($VideoID): bool
    {
        return (in_array($VideoID, $this->userLikedList()['userUnliked'])) ? true : false;
    }

    private function singleItem($Item, $singleRow, $SeasonCount): array
    {

        if (!$this->contentLevelControl($Item['video_level'])) {
            return [
                'ItemID' => 0
            ];
        }

        return  [
            'ItemID' => $singleRow['id'],
            'ItemToken' => $singleRow['video_token'],
            'Video' => [
                'SeasonCount' => $SeasonCount,
                'Name' => parent::VideoTranslation($Item['video_name'], $Item['video_translations'], 'name'),
                'MatchScore' => $this->matchScore($Item),
                'Level' => $this->AdulthoodLevel($Item['video_level']),
                'Categories' => $this->videoCategories($Item['video_categories'])
            ],
            'Watch' => [
                'Trailer' => $singleRow['video_short_source'],
                'Link' => PATH . 'watch/87654' . $singleRow['id']
            ],
            'Images' => [
                'Main' => $this->videoImages($singleRow['video_images'])['videoLogo'],
                'ImagesArray' => $this->videoImages($singleRow['video_images'])['videoImages']
            ],
            'ListAdded' => $this->inProfileList($Item['id']),
            'Liked' => $this->userLiked($Item['id']),
            'Unliked' => $this->userUnliked($Item['id']),
            'PercentageWatched' => $this->watchedDuration($singleRow['video_duration'], $singleRow['id'])
        ];
    }

    private function singleMovie($Item): array
    {
        return $this->singleItem($Item, $Item, 0);
    }

    private function singleEpisode($singleRow): array
    {
        $SeriesID = $singleRow['series_id'];
        $Item = $this->db->query("SELECT video_type,video_name,video_level,video_categories,video_translations,id,video_duration FROM series_and_movies WHERE id = '{$SeriesID}'
        and video_type='series' ")->fetch(PDO::FETCH_ASSOC);

        if (!$Item) {
            return [
                'ItemID' => 0
            ];
        }

        $SessionQuery = $this->db->query(
            "SELECT id FROM series_and_movies WHERE series_id='{$SeriesID}' and video_type='season' ",
            PDO::FETCH_ASSOC
        );
        $SeasonCount = $SessionQuery->rowCount();

        return $this->singleItem($Item, $singleRow, $SeasonCount);
    }

    private function singleSeason($singleSeason): array
    {
        $SeriesID = $singleSeason['series_id'];
        $Item = $this->db->query("SELECT video_type,video_name,video_level,video_categories,video_translations,id,video_duration FROM series_and_movies WHERE id = '{$SeriesID}'
        and video_type='series' ")->fetch(PDO::FETCH_ASSOC);

        if (!$Item) {
            return [
                'ItemID' => 0
            ];
        }

        $singleRow = $this->db->query("SELECT id,video_token,video_short_source,video_images,video_duration FROM series_and_movies WHERE series_season_id='{$singleSeason['id']}' and video_type='episode' ")->fetch(PDO::FETCH_ASSOC);
        $SessionQuery = $this->db->query(
            "SELECT id FROM series_and_movies WHERE series_id='{$SeriesID}' and video_type='season' ",
            PDO::FETCH_ASSOC
        );
        $SeasonCount = $SessionQuery->rowCount();

        return $this->singleItem($Item, $singleRow, $SeasonCount);
    }

    private function singleSeries($singleSeries): array
    {

        $Item = $singleSeries;
        $SeriesID = $singleSeries['id'];
        $singleRow = $this->db->query("SELECT id,video_token,video_short_source,video_images,video_duration FROM series_and_movies WHERE series_id='{$singleSeries['id']}' and video_type='episode' ")->fetch(PDO::FETCH_ASSOC);
        $SessionQuery = $this->db->query(
            "SELECT id FROM series_and_movies WHERE series_id='{$SeriesID}' and video_type='season' ",
            PDO::FETCH_ASSOC
        );
        $SeasonCount = $SessionQuery->rowCount();

        return $this->singleItem($Item, $singleRow, $SeasonCount);
    }

    private function createItems(): array
    {
        $ItemsObject     = [];
        $ItemsObjectTemp = [];
        $UniqNamesArray  = [];

        if (is_array($this->rows)) {
            foreach ($this->rows as $key => $singleRow) {

                if ($singleRow['video_type'] == 'movie') {
                    $singleObject = $this->singleMovie($singleRow);
                }

                if ($singleRow['video_type'] == 'episode') {
                    $singleObject = $this->singleEpisode($singleRow);
                }

                if ($singleRow['video_type'] == 'season') {
                    $singleObject = $this->singleSeason($singleRow);
                }

                if ($singleRow['video_type'] == 'series') {
                    $singleObject = $this->singleSeries($singleRow);
                }

                if ($singleObject['ItemID'] == 0) {
                    continue;
                }

                array_push($ItemsObjectTemp, $singleObject);
            }
        }

        if (is_array($ItemsObjectTemp) && count($ItemsObjectTemp) > 0) {
            foreach ($ItemsObjectTemp as $key => $singleItem) {
                if (!in_array($singleItem['Video']['Name'], $UniqNamesArray)) {
                    array_push($ItemsObject, $singleItem);
                    array_push($UniqNamesArray, $singleItem['Video']['Name']);
                }
            }
        }

        return $ItemsObject;
    }

    public function responseJson(): string
    {
        $Items = $this->createItems();
        $ResponseObject = [
            'ItemCount' => count($Items),
            'Items' => $Items,
            'Jwt' => parent::WatchReferer(),
            'SomeTexts' => [
                'addList' => parent::lang('531_add-to-my'),
                'removeList' => parent::lang('532_remove-from-my'),
                'liked' => parent::lang('533_i-love'),
                'unliked' => parent::lang('534_not-for'),
                'watch' => parent::lang('535_watch'),
                'Season' => parent::lang('64_season'),
                'NoItem' => parent::lang('65_we-couldn-t-find-anything'),
                'More' => parent::lang('536_more')
            ]
        ];

        return json_encode($ResponseObject);
    }
}