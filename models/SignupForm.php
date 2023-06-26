<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
    public $name;
    public $email;
    public $city_id;
    public $is_performer;
    public $password;
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'city_id'], 'required'],
            [['password_repeat'], 'safe'],
            [['password'], 'required', 'on' => 'register'],
            [['password'], 'compare', 'on' => 'register'],
            [['name'], 'string', 'max' => 128],
            [['email'], 'unique', 'targetClass' => User::class],
            [['email'], 'email'],
            [['password'], 'string', 'min' => 8],
            [['is_performer'], 'boolean'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'city_id' => 'Город',
            'is_performer' => 'я собираюсь откликаться на заказы',
        ];
    }

    /**
     * Registers and authorizes the new user
     * 
     * @return bool — true if the user is successfully saved in the DB
     * and lhas been logged in
     */
    public function signup(): bool
    {
        if ($this->validate()) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);

            $user = new User;
            $user->attributes = $this->attributes;

            if ($user->create()) {
                return Yii::$app->user->login($user);
            };
        }
    }
}
