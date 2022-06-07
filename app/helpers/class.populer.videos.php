<?php

class NetfluxPopulerItems extends StaticFunctions
{

    protected $db;
    protected $ProfileID;
    protected $VideoID;
    protected $VideoType;

    public function setDb($DataBase): void
    {
        $this->db = $DataBase;
    }

    public function setProfileID($Id): void
    {
        $this->ProfileID = $Id;
    }

    public function setVideoID($Id): void
    {
        $this->VideoID = $Id;
    }

    public function setVideoType($Type): void
    {
        $this->VideoType = $Type;
    }

    public function Popular($Type): void
    {
        $ItemPopularity = $this->videoPopularity();
        $ListListed = json_decode($ItemPopularity['popularity_votes'], true)[$Type];
        $IsVoted = false;
        foreach ($ListListed as $key => $profile) {
            if ($profile == $this->ProfileID) {
                $IsVoted = true;
                break;
            }
        }
        if ($IsVoted == false) {
            $this->Vote($Type, $ItemPopularity);
        }
    }

    private function Vote($Type, $ItemRow): void
    {
        $ItemRow[$Type . '_count']++;
        $ListCount   = $ItemRow['list_count'];
        $LikeCount   = $ItemRow['like_count'];
        $UnlikeCount = $ItemRow['unlike_count'];

        $Votes = json_decode($ItemRow['popularity_votes'], true);
        array_push($Votes[$Type], $this->ProfileID);
        $VotesJson = json_encode($Votes);

        $PopularityScore = $ItemRow['popularity_score'];

        if ($Type == 'list') {
            $PopularityScore += $this->popularPoints()['listAdded'];
        }

        if ($Type == 'like') {
            $PopularityScore += $this->popularPoints()['liked'];
        }

        if ($Type == 'unlike') {
            $PopularityScore = $PopularityScore - $this->popularPoints()['unliked'];
        }

        if ($PopularityScore < 1) {
            $PopularityScore = 0;
        }

        $UpdatePopular = $this->db->prepare("UPDATE popular_videos SET
                list_count = ?,
                like_count = ?,
                unlike_count = ?,
                popularity_score = ?,
                popularity_votes = ? WHERE id='{$ItemRow['id']}' ");
        $update = $UpdatePopular->execute(array(
            $ListCount, $LikeCount, $UnlikeCount, $PopularityScore, $VotesJson
        ));
    }

    public function GetPopulerItems($VideoType = 'all', $VideoGenre = 'all'): array
    {
        $CurrentDate = $this->currentDate();
        $PopulerItems = [];
        $SqlQuery = "SELECT id,video_type,video_categories,popularity_score,video_id from popular_videos WHERE popular_month='{$CurrentDate['month']}' and popular_year='{$CurrentDate['year']}' ";
        if ($VideoType == 'movie') $SqlQuery  .= " and video_type='movie' ";
        if ($VideoType == 'series') $SqlQuery .= " and video_type='series' ";
        if ($VideoGenre != 'all') $SqlQuery   .= " and video_categories LIKE '%\"" . $VideoGenre . "\"%' ";
        $SqlQuery .= " ORDER by popularity_score DESC LIMIT 6";
        $GetPopulerItems = $this->db->query($SqlQuery, PDO::FETCH_ASSOC);
        if ($GetPopulerItems->rowCount()) {
            foreach ($GetPopulerItems as $key => $row) {
                array_push($PopulerItems, $row['video_id']);
            }
        }

        return $PopulerItems;
    }

    public function SingleItemPopularity($ItemID): int
    {
        $Order = 0;
        $CurrentDate = $this->currentDate();
        $GetSingleItem = $this->db->query("SELECT popularity_score,video_type from popular_videos WHERE video_id='{$ItemID}' and popular_month='{$CurrentDate['month']}' and popular_year='{$CurrentDate['year']}' order by id DESC ")->fetch(PDO::FETCH_ASSOC);

        if ($GetSingleItem) {
            $PopularityPoint = $GetSingleItem['popularity_score'];
            $VideoType = $GetSingleItem['video_type'];
            $GetPopularItems = $this->db->query("SELECT id from popular_videos WHERE popular_month='{$CurrentDate['month']}' and popular_year='{$CurrentDate['year']}' and video_type='{$VideoType}' and popularity_score > '{$PopularityPoint}' GROUP by popularity_score order by popularity_score DESC ", PDO::FETCH_ASSOC);
            $HigherItemCount = $GetPopularItems->rowCount();
            if ($HigherItemCount < 30) {
                $Order += ($HigherItemCount + 1);
            }
        }

        return $Order;
    }

    private function currentDate(): array
    {
        return [
            'month' => date('m'),
            'year'  => date('Y')
        ];
    }

    private function popularPoints(): array
    {
        // Adding the video to the list earns 1 point.
        // Adding the video to the likes earns 3 points.
        // Adding the video to the disliked ones loses 2 points.
        return [
            'listAdded' => 1,
            'liked' => 3,
            'unliked' => 2
        ];
    }

    private function videoPopularity(): array
    {
        $CurrentDate = $this->currentDate();
        $GetPopularData = $this->db->query("SELECT * from popular_videos WHERE video_id='{$this->VideoID}' and popular_month='{$CurrentDate['month']}' and popular_year='{$CurrentDate['year']}' ")->fetch(PDO::FETCH_ASSOC);
        if (!$GetPopularData) $GetPopularData = $this->createPopularObject();
        return $GetPopularData;
    }

    private function getDropRate(): int
    {
        $GetDropRate = $this->db->query("SELECT * FROM system_settings WHERE id =1 ")->fetch(PDO::FETCH_ASSOC);
        return ceil($GetDropRate['popularity_drop_rate']);
    }

    private function getPreviousScore(): int
    {
        $PreviousScore = 0;
        $DropRate = $this->getDropRate();
        $GetPreviousRank = $this->db->query("SELECT popularity_score from popular_videos WHERE video_id='{$this->VideoID}' order by id DESC ")->fetch(PDO::FETCH_ASSOC);
        if ($GetPreviousRank) {
            $PreviousScore = $GetPreviousRank['popularity_score'] * ($DropRate / 100);
        }
        return round($PreviousScore);
    }

    private function createPopularObject(): array
    {
        $PreviousScore = $this->getPreviousScore();
        $CurrentDate = $this->currentDate();
        $VideoCategories = $this->db->query("SELECT video_categories from series_and_movies WHERE id='{$this->VideoID}'")->fetch(PDO::FETCH_ASSOC)['video_categories'];
        $InsertPopuler = $this->db->prepare("INSERT INTO popular_videos SET
                video_id = ?,
                video_type = ?,
                video_categories = ?,
                list_count = ?,
                like_count = ?,
                unlike_count = ?,
                popular_month = ?,
                popular_year = ?,
                popularity_score = ?");
        $insert = $InsertPopuler->execute(array(
            $this->VideoID, $this->VideoType, $VideoCategories, 0, 0, 0, $CurrentDate['month'], $CurrentDate['year'],  $PreviousScore
        ));

        $InsertedID = $this->db->lastInsertId();
        $GetInsertedRow = $this->db->query("SELECT * from popular_videos WHERE video_id='{$this->VideoID}' and popular_month='{$CurrentDate['month']}' and popular_year='{$CurrentDate['year']}' ")->fetch(PDO::FETCH_ASSOC);
        return $GetInsertedRow;
    }
}