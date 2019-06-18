<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\Location;
use app\models\User;

class LocationController extends ApiController
{
    public $modelClass = 'app\models\Location';

//    public function actionAge($id)
//    {
//        $t = 1544576406;
//        $user = User::find()->where(["id" => $id])->asArray()->one();
//
//        //echo "<pre>";
//        //print_r($user);
//
//        foreach ($user as $u) {
//            $a = $t - $user['birth_date'];
//            $age = intval($a/31536000);
//        }
//        return $age;
//    }

    public function actionAvg($id)
    {
//        var_dump($id);
//        die();
        $arr = [];
        $arr['Location.id'] = $id;
        $fromdate = [];
        $todate = [];
        $fromage = [];
        $toage = [];


        if (!empty($_GET["gender"])) {
            $arr['User.gender'] = $_GET["gender"];
        }
        if (!empty($_GET["fromDate"])) {
            $fromdate = ['>', 'Visit.visited_at', $_GET["fromDate"]];
        }
        if (!empty($_GET["toDate"])) {
            $todate = ['<', 'Visit.visited_at', $_GET["toDate"]];
        }
        if (!empty($_GET["fromAge"])) {
            $fromage = ['>', 'User.age', $_GET["fromAge"]];
        }
        if (!empty($_GET["fromAge"])) {
            $toage = ['>', 'User.age', $_GET["fromAge"]];
        }


        $location = Location::find()
            ->select('avg(Visit.mark) as avg' )
            ->rightJoin('Visit', 'Location.id = Visit.location')
            ->rightJoin('User', 'Visit.user = User.id')
            ->where($arr)
            ->andWhere($fromdate)
            ->andWhere($todate)
            ->andWhere($fromage)
            ->andWhere($toage)
            ->groupBy('Location.id')
            ->asArray(true)
            ->one();

//        echo "<pre>";
//        print_r($location);

        return $location;




    }
}