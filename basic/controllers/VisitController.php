<?php

namespace app\controllers;

use app\controllers\ApiController;
use Yii;
use yii\web\HttpException;
use app\models\Visit;

class VisitController extends ApiController
{
    public $modelClass = 'app\models\Visit';

    public function actionCreate()
    {
        $model = new Visit;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $isExists = Visit::find()->where(['id' => $model->id])->exists();
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
        $model = Visit::find()->where(["id" => $id])->one();
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