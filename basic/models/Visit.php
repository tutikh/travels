<?php

namespace app\models;

use Yii;

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location', 'user', 'visited_at', 'mark'], 'required'],
            [['location', 'user', 'mark'], 'integer'],
            [['visited_at'], 'string', 'max' => 150],
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
}
