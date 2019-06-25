<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;

/**
 * This is the model class for table "Visit".
 *
 * @property int $id
 * @property int $location
 * @property int $user
 * @property string $visited_at
 * @property int $mark
 */
class Visit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Visit';
    }

//    public function behaviors()
//    {
//        return [
//            'typecast' => [
//                'class' => AttributeTypecastBehavior::className(),
//                'attributeTypes' => [
//                    'mark' => AttributeTypecastBehavior::TYPE_INTEGER,
//                    'visited_at' => AttributeTypecastBehavior::TYPE_INTEGER,
//                ],
//                'typecastAfterValidate' => true,
//                'typecastBeforeSave' => false,
//                'typecastAfterFind' => true,
//            ],
//        ];
//    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location', 'user', 'visited_at', 'mark'], 'required'],
            [['location', 'user', 'mark', 'visited_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location' => 'Location',
            'user' => 'User',
            'visited_at' => 'Visited At',
            'mark' => 'Mark',
        ];
    }
    public function getLocations() {
        return $this -> hasOne(Location::className(), ['id' => 'location']);
    }

    public function getUser() {
        return $this -> hasOne(User::className(), ['id' => 'user']);
    }

    public function scenarios()
    {
        $scenarios['default'] = ['id', 'location', 'user', 'visited_at', 'mark'];
        $scenarios['safe'] = ['location', 'user', 'visited_at', 'mark'];
        return $scenarios;
    }

//    public function afterFind() {
//        parent::afterFind();
//        $this->mark = (int) $this->mark;
//        $this->visited_at = (int) $this->visited_at;
//    }
}
