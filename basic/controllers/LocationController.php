<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\Location;

class LocationController extends ApiController
{
    public $modelClass = 'app\models\Location';

    public function actionAvg($id)
    {
        $location = Location::find()->where(["id" => $id])->with("visits")->asArray()->one();

        //echo "<pre>";
        //print_r($location);



        foreach ($location['visits'] as $loc) {
            $mark[] = $loc['mark'];
            $avg = [
                "avg" => array_sum($mark) / count($mark)
            ];
        }

        return $avg;
    }
}