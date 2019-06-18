<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\Location;
use app\models\User;

class LocationController extends ApiController
{
    public $modelClass = 'app\models\Location';

    public function actionAge($id)
    {
        $t = 1544576406;
        $user = User::find()->where(["id" => $id])->asArray()->one();

        //echo "<pre>";
        //print_r($user);

        foreach ($user as $u) {
            $a = $t - $user['birth_date'];
            $age = intval($a/31536000);
        }
        return $age;
    }

    public function actionAvg2($id)
    {
//        var_dump($id);
//        die();
        $arr = [];
        $arr['Location.id'] = $id;
        $a = [];
        $b = [];
        $c = [];


        if (!empty($_GET["gender"])) {
            $arr['User.gender'] = $_GET["gender"];
        }
        if (!empty($_GET["fromDate"])) {
            $a = ['>', 'Visit.visited_at', $_GET["fromDate"]];
        }
        if (!empty($_GET["toDate"])) {
            $b = ['<', 'Visit.visited_at', $_GET["toDate"]];
        }
//        if (!empty($_GET["fromAge"])) {
//            $loc = Location::find()->where(["id" => $id])->with("visits.user")->asArray()->one();
//            foreach ($loc['visits'] as $l) {
//                $c = ['>', $this->actionAge($l['user']['id']), $_GET["fromAge"]];
//            }
//        }


        $location = Location::find()
            ->select('avg(Visit.mark) as avg' )
            ->rightJoin('Visit', 'Location.id = Visit.location')
            ->rightJoin('User', 'Visit.user = User.id')
            ->where($arr)
            ->andWhere($a)
            ->andWhere($b)
            ->andWhere($c)
            ->groupBy('Location.id')
            ->asArray(true)
            ->one();

//        echo "<pre>";
//        print_r($location);

        return $location;




    }

    public function actionAvg($id)
    {
        $location = Location::find()->where(["id" => $id])->with("visits.user")->asArray()->one();
        //echo "<pre>";
        //print_r($location);

//        foreach ($location['visits'] as $loc) {
//            $mark[] = $loc['mark'];
//            $avg = [
//                "avg" => round(array_sum($mark) / count($mark), 5),
//                "age" => $this->actionAge($loc['user']['id'])
//            ];
//        }

        if (!empty($_GET["gender"])) {
            foreach ($location['visits'] as $loc) {
                if ($loc['user']['gender'] == $_GET["gender"]) {
                    $mark[] = $loc['mark'];
                    $data = [
                        "avg" => round(array_sum($mark) / count($mark), 5),
                    ];
                }
            } return $data;
        }elseif (!empty($_GET["fromDate"])) {
            foreach ($location['visits'] as $loc) {
                if ($loc['visited_at'] > $_GET["fromDate"]) {
                    $mark[] = $loc['mark'];
                    $data = [
                        "avg" => round(array_sum($mark) / count($mark), 5),
                    ];
                }
            } return $data;
        }elseif (!empty($_GET["toDate"])) {
            foreach ($location['visits'] as $loc) {
                if ($loc['visited_at'] < $_GET["toDate"]) {
                    $mark[] = $loc['mark'];
                    $data = [
                        "avg" => round(array_sum($mark) / count($mark), 5),
                    ];
                }
            } return $data;
        }elseif (!empty($_GET["fromAge"])) {
            foreach ($location['visits'] as $loc) {
                if ($this->actionAge($loc['user']['id']) > $_GET["fromAge"]) {
                    $mark[] = $loc['mark'];
                    $data = [
                        "avg" => round(array_sum($mark) / count($mark), 5),
                    ];
                }
            } return $data;
        }
    }
}