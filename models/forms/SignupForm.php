<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\City;

class SignupForm extends Model
{
    public $name;
    public $email;
    public $cityId;
    public $isPerformer;
    public $password;
    public $passwordRepeat;

    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_NAME_LENGTH = 128;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'cityId'], 'required'],
            [['passwordRepeat'], 'safe'],
            [['password'], 'required', 'on' => 'register'],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password', 'on' => 'register'],
            [['name'], 'string', 'max' => Self::MAX_NAME_LENGTH],
            [['email'], 'unique', 'targetClass' => User::class],
            [['email'], 'email'],
            [['password'], 'string', 'min' => Self::MIN_PASSWORD_LENGTH],
            [['isPerformer'], 'boolean'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['cityId' => 'id']],
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
            'passwordRepeat' => 'Повтор пароля',
            'cityId' => 'Город',
            'isPerformer' => 'я собираюсь откликаться на заказы',
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
            $user->name = $this->name;
            $user->email = $this->email;
            $user->password = $this->password;
            $user->city_id = $this->cityId;
            $user->is_performer = $this->isPerformer;

            if ($user->save(false)) {
                return Yii::$app->user->login($user);
            };
        }

        return false;
    }
}
