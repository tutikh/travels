<?php

namespace app\controllers;

use app\controllers\ApiController;
use Yii;
use yii\web\HttpException;
use app\models\Visit;
use yii\web\Response;

class VisitController extends ApiController
{
    public $modelClass = 'app\models\Visit';

    public function actionCreate()
    {
        $model = new Visit;
        $model->scenario = 'default';
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $isExists = Visit::find()->where(['id' => $model->id])->exists();
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
        $model = Visit::find()->where(["id" => $id])->one();
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
        $model = Visit::find()->where(["id" => $id])->one();
        if (!$model) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(404);
            $response->format = Response::FORMAT_HTML;
            return '';
        } else return $model;
    }
}