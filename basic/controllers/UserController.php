<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\User;
use app\models\Visit;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Location;

class UserController extends ApiController
{
    public $modelClass = 'app\models\User';

    public function actionVisits($id)
    {
       // $user = User::find()->where(["id" => $id])->with("visits.locations")->asArray()->one();

        $visits = Visit::find()->where(["user" => $id])->with("locations")->orderBy('visited_at', "DESC")->asArray()->all();

       // echo "<pre>";
        // print_r($visits);


        $response = [];
        $response['visits'] = [];
        foreach ($visits as $visit) {
            $data = [
                "mark" => $visit['mark'],
                "visited_at"  => $visit['visited_at'],
                "place" => $visit['locations']['place']
            ];
            $response['visits'][] = $data;
        }

        return $response;
    }
}