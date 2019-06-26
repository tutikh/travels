<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\Response;

class UserController extends ApiController
{
    public $modelClass = 'app\models\User';

    public function actionVisits($id)
    {
        $model = User::find()->where(["id" => $id])->one();
        $response = Yii::$app->getResponse();
        $response->setStatusCode(400);
        $response->format = Response::FORMAT_HTML;

        if ($model == null) {
            $response->setStatusCode(404);
            return '';
        }

        $arr = [];
        $arr['User'] = $id;
        $fromdate = [];
        $todate = [];
        $todistance = [];

        if (isset($_GET["country"]) ) {
            $arr['Location.country'] = $_GET["country"];
        }
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
        if (isset($_GET["toDistance"])) {
            $todistance = ['<', 'Location.distance', $_GET["toDistance"]];
            if (!is_numeric($_GET["toDistance"])) {
                return '';
            }
        }
        if ($_GET == '') {
            return '';
        }

        $visits = (new \yii\db\Query())
            ->select('Visit.mark, Visit.visited_at, Location.place' )
            ->from('Visit')
            ->rightJoin('Location', 'Visit.location = Location.id')
            ->rightJoin('User', 'Visit.user = User.id')
            ->where($arr)
            ->andWhere($fromdate)
            ->andWhere($todate)
            ->andWhere($todistance)
            ->orderBy('Visit.visited_at')
            ->all();

        if ($visits) {
            $response->setStatusCode(200);
            $response->format = Response::FORMAT_JSON;

            foreach ($visits as $key => $visit) {
                $visits[$key]['mark'] = (int) $visit['mark'];
                $visits[$key]['visited_at'] = (int) $visit['visited_at'];
            }

            if (is_null($visits)) {
                $res['visits']=[];
            } else $res['visits']=$visits;

            return $res;
        }
    }

    public function actionCreate()
    {
        $model = new User;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $response = Yii::$app->getResponse();
        $response->setStatusCode(400);
        $response->format = Response::FORMAT_HTML;
        if (User::find()->where(['id' => $model->id])->exists() || is_null($model->id)) {
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
        $model = User::find()->where(["id" => $id])->one();
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
        $model = User::find()->where(["id" => $id])->one();

        if ($model == null) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(404);
            $response->format = Response::FORMAT_HTML;
            return '';
        } else return $model;
    }
}