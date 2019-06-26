<?php

namespace app\controllers;

use Yii;
use app\models\Visit;
use yii\web\Response;

class VisitController extends ApiController
{
    public $modelClass = 'app\models\Visit';

    public function actionCreate()
    {
        $model = new Visit;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $response = Yii::$app->getResponse();
        $response->setStatusCode(400);
        $response->format = Response::FORMAT_HTML;
        if (Visit::find()->where(['id' => $model->id])->exists() || is_null($model->id)) {
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
        $model = Visit::find()->where(["id" => $id])->one();
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
        $model = Visit::find()->where(["id" => $id])->one();
        if (!$model) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(404);
            $response->format = Response::FORMAT_HTML;
            return '';
        } else return $model;
    }
}