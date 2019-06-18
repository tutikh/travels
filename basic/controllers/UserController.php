<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\User;
use app\models\Visit;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Location;
use yii\web\BadRequestHttpException;
use Yii;

class UserController extends ApiController
{
    public $modelClass = 'app\models\User';

    public function actionVisits($id)
    {
        $arr = [];
        $arr['User'] = $id;
        $fromdate = [];
        $todate = [];
        $todistance = [];

        if (!empty($_GET["country"])) {
            $arr['Location.country'] = $_GET["country"];
        }
        if (!empty($_GET["fromDate"])) {
            $fromdate = ['>', 'Visit.visited_at', $_GET["fromDate"]];
        }
        if (!empty($_GET["toDate"])) {
            $todate = ['<', 'Visit.visited_at', $_GET["toDate"]];
        }
        if (!empty($_GET["toDistance"])) {
            $todistance = ['<', 'Location.distance', $_GET["toDistance"]];
        }

        $visits = Visit::find()
            ->select('Visit.mark, Visit.visited_at, Location.place' )
            ->rightJoin('Location', 'Visit.location = Location.id')
            ->rightJoin('User', 'Visit.user = User.id')
            ->where($arr)
            ->andWhere($fromdate)
            ->andWhere($todate)
            ->andWhere($todistance)
            ->orderBy('Visit.visited_at')
            ->asArray(true)
            ->all();

//        echo "<pre>";
//        print_r($visits);
        $response['visits']=$visits;

        return $response;
    }
}