<?php

namespace app\controllers;

use app\models\Location;
use Yii;
use yii\web\Response;

class LocationController extends ApiController
{
    public $modelClass = 'app\models\Location';

    public function actionAvg($id)
    {

        $model = Location::find()->where(["id" => $id])->one();
        $response = Yii::$app->getResponse();
        $response->setStatusCode(400);
        $response->format = Response::FORMAT_HTML;
        if (!$model) {
            $response->setStatusCode(404);
            return '';
        }

        $arr = [];
        $arr['Location.id'] = $id;
        $fromdate = [];
        $todate = [];
        $fromage = [];
        $toage = [];


        if (isset($_GET["fromDate"])) {
            $fromdate = ['>', 'Visit.visited_at', $_GET["fromDate"]];
            if (!is_numeric($_GET["fromDate"])) {
                return '';
            }
        }
        if (isset($_GET["toDate"])) {
            $todate = ['<', 'Visit.visited_at', $_GET["toDate"]];
            if (!is_numeric($_GET["toDate"])) {
                return '';
            }
        }
        if (isset($_GET["fromAge"])) {
            $fromage = ['>=', 'User.age', $_GET["fromAge"]];
            if (!is_numeric($_GET["fromAge"])) {
                return '';
            }
        }
        if (isset($_GET["toAge"])) {
            $toage = ['<', 'User.age', $_GET["toAge"]];
            if (!is_numeric($_GET["toAge"])) {
                return '';
            }
        }
        if ($_GET == '') {
            return '';
        }
        if (isset($_GET["gender"])) {
            $arr['User.gender'] = $_GET["gender"];
            if (!ctype_alpha($_GET["gender"]) || iconv_strlen($_GET["gender"])!=1) {
                return '';
            }
        }


        $location = (new \yii\db\Query())
            ->select(["round(avg(Visit.mark), 5) as avg"])
            ->from('Location')
            ->rightJoin('Visit', 'Location.id = Visit.location')
            ->rightJoin('User', 'Visit.user = User.id')
            ->where($arr)
            ->andWhere($fromdate)
            ->andWhere($todate)
            ->andWhere($fromage)
            ->andWhere($toage)
            ->groupBy('Location.id')
            ->one();


        $res['avg'] = (float) $location['avg'];
            if (filter_var($res['avg'], FILTER_VALIDATE_INT)) {
                $response->setStatusCode(200);
            echo sprintf('{ "avg": %.1f }', $location['avg']);
            die();
        } elseif (is_null($location['avg'])) {
                $response->setStatusCode(200);
                echo '{ "avg": 0.0 }';
            }

            if ($location){
                $response->format = Response::FORMAT_JSON;
                $response->setStatusCode(200);
                return $res;
            }
    }

    public function actionCreate()
    {
        $model = new Location;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $response = Yii::$app->getResponse();
        $response->setStatusCode(400);
        $response->format = Response::FORMAT_HTML;
        if (Location::find()->where(['id' => $model->id])->exists() || is_null($model->id)) {
            return '';
        }
        if ($model->save()) {
            $response->setStatusCode(200);
            return '';
        } elseif ($model->hasErrors()) {
            return '';
        }
    }

    public function actionUpdate($id)
    {
        $model = Location::find()->where(["id" => $id])->one();
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_HTML;
        if (!$model) {
            $response->setStatusCode(404);
            return '';
        }
        $model->scenario = 'safe';
        $data = Yii::$app->getRequest()->getBodyParams();
        if (empty($data)) {
            $response->setStatusCode(400);
            return '';
        }
        $model->load($data, '');

        if ($model->save()) {
            $response->setStatusCode(200);
            return '';
        } elseif ($model->hasErrors()) {
            $response->setStatusCode(400);
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