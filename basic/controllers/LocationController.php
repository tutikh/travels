<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\Location;
use app\models\User;
use Yii;
use yii\web\HttpException;

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
//        var_dump($_GET);
//        die();
        $arr = [];
        $arr['Location.id'] = $id;
        $fromdate = [];
        $todate = [];
        $fromage = [];
        $toage = [];


        if (isset($_GET["gender"])) {
            $arr['User.gender'] = $_GET["gender"];
            if (!ctype_alpha($_GET["gender"])) {
                throw new HttpException('400');
            }
        }
        if (isset($_GET["fromDate"])) {
            $fromdate = ['>', 'Visit.visited_at', $_GET["fromDate"]];
            if (!is_numeric($_GET["fromDate"])) {
                throw new HttpException('400');
            }
        }
        if (isset($_GET["toDate"])) {
            $todate = ['<', 'Visit.visited_at', $_GET["toDate"]];
            if (!is_numeric($_GET["toDate"])) {
                throw new HttpException('400');
            }
        }
        if (isset($_GET["fromAge"])) {
            $fromage = ['>', 'User.age', $_GET["fromAge"]];
            if (!is_numeric($_GET["fromAge"])) {
                throw new HttpException('400');
            }
        }
        if (isset($_GET["toAge"])) {
            $toage = ['<', 'User.age', $_GET["toAge"]];
            if (!is_numeric($_GET["toAge"])) {
                throw new HttpException('400');
            }
        }
        if ($_GET == '') {
            throw new HttpException('400');
        }


        $location = Location::find()
            ->select('avg(Visit.mark) as avg')
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

        if (is_null($location)) {
            $response['avg'] = 0;
        } else $response['avg'] = round($location['avg'], 5);


        return $response;

    }

    public function actionCreate()
    {
        $model = new Location;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $isExists = Location::find()->where(['id' => $model->id])->exists();
        if ($isExists) {
            throw new HttpException('400');
        }
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            echo '{}';
        } elseif ($model->hasErrors()) {
            throw new HttpException('400');
        }
    }

    public function actionUpdate($id)
    {
        $model = Location::find()->where(["id" => $id])->one();
        $model->scenario = 'safe';
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model) {
            throw new HttpException('404');
        }
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            echo '{}';
        } elseif ($model->hasErrors()) {
            throw new HttpException('400');
        }
    }
}