<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $birth_date
 * @property int $age
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'email', 'first_name', 'last_name', 'gender', 'birth_date'], 'required'],
            [['email'], 'string', 'max' => 100],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 1],
            [['age', 'birth_date'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'birth_date' => 'Birth Date',
            'age' => 'Age',
        ];
    }

    public function getVisits() {
        return $this -> hasMany(Visit::className(), ['user' => 'id']);
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['age']);
        return $fields;
    }

    public function scenarios()
    {
        $scenarios['default'] = ['id', 'email', 'first_name', 'last_name', 'gender', 'birth_date', 'age'];
        $scenarios['safe'] = ['email', 'first_name', 'last_name', 'gender', 'birth_date', 'age'];
        return $scenarios;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->age = number_format(((1544576406 - ($this->birth_date))/31536000),  2, '.', '');
            return true;
        }
        return false;
    }


}
