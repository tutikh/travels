<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Location".
 *
 * @property int $distance
 * @property string $city
 * @property string $place
 * @property int $id
 * @property string $country
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['distance', 'city', 'place', 'country'], 'required'],
            [['distance'], 'integer'],
            [['place'], 'string'],
            [['city', 'country'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'distance' => 'Distance',
            'city' => 'City',
            'place' => 'Place',
            'id' => 'ID',
            'country' => 'Country',
        ];
    }

    public function getVisits() {
        return $this -> hasMany(Visit::className(), ['location' => 'id']);
    }

    public function scenarios()
    {
        $scenarios['default'] = ['id', 'distance', 'city', 'place', 'country'];
        $scenarios['safe'] = ['distance', 'city', 'place', 'country'];
        return $scenarios;
    }
}
