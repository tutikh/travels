<?php

namespace app\controllers;

use app\controllers\ApiController;
use app\models\User;
use app\models\Visit;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Location;
use yii\web\BadRequestHttpException;
use Yii;

class UserController extends ApiController
{
    public $modelClass = 'app\models\User';

    public function actionVisits($id)
    {
        //print_r($_GET);

        //echo Yii::$app->request->get('toDistance');
        //echo "\n";
       // $user = User::find()->where(["id" => $id])->with("visits.locations")->asArray()->one();

        $visits = Visit::find()->where(["user" => $id])->with("locations")->orderBy('visited_at', "DESC")->asArray()->all();





        if (!empty($_GET["country"]) && !empty($_GET["toDistance"]) && !empty($_GET["fromDate"]) && !empty($_GET["toDate"])) {
            foreach ($visits as $vis) {
                if ($vis['locations']['country'] == $_GET["country"] && $vis['locations']['distance'] < $_GET["toDistance"] && $vis['visited_at'] > $_GET["fromDate"] && $vis['visited_at'] < $_GET["toDate"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            }
            return $res;
        } elseif (!empty($_GET["country"]) && !empty($_GET["toDistance"]) && !empty($_GET["fromDate"])) {
            foreach ($visits as $vis) {
                if ($vis['locations']['country'] == $_GET["country"] && $vis['locations']['distance'] < $_GET["toDistance"] && $vis['visited_at'] > $_GET["fromDate"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            } return $res;
        } elseif (!empty($_GET["country"]) && !empty($_GET["toDistance"]) && !empty($_GET["toDate"])) {
            foreach ($visits as $vis) {
                if ($vis['locations']['country'] == $_GET["country"] && $vis['locations']['distance'] < $_GET["toDistance"] && $vis['visited_at'] < $_GET["toDate"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            } return $res;
        } elseif (!empty($_GET["country"]) && !empty($_GET["fromDate"]) && !empty($_GET["toDate"])) {
            foreach ($visits as $vis) {
                if ($vis['locations']['country'] == $_GET["country"] && $vis['visited_at'] > $_GET["fromDate"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            } return $res;
        } elseif (!empty($_GET["country"])) {
            foreach ($visits as $vis) {
                if ($vis['locations']['country'] == $_GET["country"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            } return $res;
        } elseif (!empty($_GET["toDistance"])) {
            foreach ($visits as $vis) {
                if ($vis['locations']['distance'] < $_GET["toDistance"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            } return $res;
        } elseif (!empty($_GET["fromDate"]) && !empty($_GET["toDate"])) {
            foreach ($visits as $vis) {
                if ($vis['visited_at'] > $_GET["fromDate"] && $vis['visited_at'] < $_GET["toDate"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            }
            return $res;
        } elseif (!empty($_GET["fromDate"])) {
            foreach ($visits as $vis) {
                if ($vis['visited_at'] > $_GET["fromDate"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            }
            return $res;
        } elseif (!empty($_GET["toDate"])) {
            foreach ($visits as $vis) {
                if ($vis['visited_at'] < $_GET["toDate"]) {
                    $data = [
                        "mark" => $vis['mark'],
                        "visited_at" => $vis['visited_at'],
                        "place" => $vis['locations']['place'],
                        "country" => $vis['locations']['country'],
                        "distance" => $vis['locations']['distance']
                    ];
                    $res['visits'][] = $data;
                }
            }
            return $res;
        }





                // echo "<pre>";
        // print_r($visits);


    /*    $response = [];
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

*/
    }

    public function actionNew() {

    }

}