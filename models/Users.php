<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Users".
 *
 * @property int $id
 * @property string $fio
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string|null $access_token
 * @property int|null $isAdmin
 *
 * @property Orders[] $orders
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isAdmin'], 'integer'],
            [['fio', 'email', 'password', 'phone'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 250],
            [['email'], 'unique'],
            [['fio', 'email', 'password', 'phone'], 'required'],
            ['fio', 'match', 'pattern' => '/^[А-Яа-яЁё\s\-\']+$/u', 'message' => 'Допустимы только кириллические символы, пробелы и дефисы.'],
            ['phone', 'match', 'pattern' => '/^\+?[0-9]+$/'],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'Этот email уже занят.'],
            ['password', 'string', 'min' => 7],
            ['password', 'match', 'pattern' => '/^(?=.*[0-9])(?=.*[a-zа-я])(?=.*[A-ZА-Я]).+$/u', 'message' => 'Пароль должен содержать минимум 1 цифру, 1 строчную и 1 заглавную букву.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Fio',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'access_token' => 'Access Token',
            'isAdmin' => 'Is Admin',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['customerId' => 'id']);
    }
}
