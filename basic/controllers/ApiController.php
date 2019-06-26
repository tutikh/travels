<?php

namespace app\controllers;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\rest\Controller;
use yii\filters\ContentNegotiator;

class ApiController extends Controller
{

    public $modelClass;

    public $updateScenario = Model::SCENARIO_DEFAULT;

    public $createScenario = Model::SCENARIO_DEFAULT;

    public $scenario = Model::SCENARIO_DEFAULT;


    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['POST'],
        ];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
    }

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }
}