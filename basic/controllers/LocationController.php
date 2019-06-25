<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\Location;
use app\models\User;
use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\db;

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

    /**
     * @param $id
     * @return string|\yii\console\Response|Response
     */
    public function actionAvg($id)
    {

        $model = Location::find()->where(["id" => $id])->one();
        if (!$model) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(404);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
//        var_dump($_GET);
//        die();
        $arr = [];
        $arr['Location.id'] = $id;
        $fromdate = [];
        $todate = [];
        $fromage = [];
        $toage = [];


        if (isset($_GET["fromDate"])) {
            $fromdate = ['>', 'Visit.visited_at', $_GET["fromDate"]];
            if (!is_numeric($_GET["fromDate"])) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                $response->format = Response::FORMAT_HTML;
                return '';
            }
        }
        if (isset($_GET["toDate"])) {
            $todate = ['<', 'Visit.visited_at', $_GET["toDate"]];
            if (!is_numeric($_GET["toDate"])) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                $response->format = Response::FORMAT_HTML;
                return '';
            }
        }
        if (isset($_GET["fromAge"])) {
            $fromage = ['>=', 'User.age', $_GET["fromAge"]];
            if (!is_numeric($_GET["fromAge"])) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                $response->format = Response::FORMAT_HTML;
                return '';
            }
        }
        if (isset($_GET["toAge"])) {
            $toage = ['<', 'User.age', $_GET["toAge"]];
            if (!is_numeric($_GET["toAge"])) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                $response->format = Response::FORMAT_HTML;
                return '';
            }
        }
        if ($_GET == '') {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
        if (isset($_GET["gender"])) {
            $arr['User.gender'] = $_GET["gender"];
            if (!ctype_alpha($_GET["gender"]) || iconv_strlen($_GET["gender"])!=1) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(400);
                $response->format = Response::FORMAT_HTML;
                return '';
            }
        }


        $location = Location::find()
            ->select(["round(avg(Visit.mark), 5) as avg"])
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


        $response['avg'] = (float) $location['avg'];
            if (filter_var($response['avg'], FILTER_VALIDATE_INT)) {
            $res = Yii::$app->getResponse();
            $res->format = Response::FORMAT_RAW;
            echo sprintf('{ "avg": %.1f }', $location['avg']);
            die();
        } elseif (is_null($location['avg'])) {
                $response = Yii::$app->getResponse();
                $response->format = Response::FORMAT_HTML;
                echo '{ "avg": 0.0 }';
            } else
        return $response;
    }

    public function actionCreate()
    {
        $model = new Location;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $isExists = Location::find()->where(['id' => $model->id])->exists();
        if ($isExists) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
        if (is_null($model->id)) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            $response->format = Response::FORMAT_HTML;
            return '';
        } elseif ($model->hasErrors()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
    }

    public function actionUpdate($id)
    {
        $model = Location::find()->where(["id" => $id])->one();
        if (!$model) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(404);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
        $model->scenario = 'safe';
        $data = Yii::$app->getRequest()->getBodyParams();
        if (empty($data)) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
        $model->load($data, '');

        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            $response->format = Response::FORMAT_HTML;
            return '';
        } elseif ($model->hasErrors()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(400);
            $response->format = Response::FORMAT_HTML;
            return '';
        }
    }

    public function actionView($id)
    {
        $model = Location::find()->where(["id" => $id])->one();
        if (!$model) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(404);
            $response->format = Response::FORMAT_HTML;
            return '';
        } else return $model;
    }
}