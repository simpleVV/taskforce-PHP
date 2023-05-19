<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $dt_registration
 * @property string $email
 * @property string $name
 * @property string $password
 * @property int $city_id
 * @property boolean $is_performer
 *
 * @property City $city
 * @property Contact[] $contacts
 * @property Response[] $responses
 * @property Review[] $reviews0
 * @property Task[] $tasks
 * @property UserSetting[] $userSettings
 */
class User extends \yii\db\ActiveRecord
{
    public $password_repeat;
    public $is_performer;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'password', 'password_repeat', 'city_id'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['dt_registration'], 'safe'],
            [['city_id'], 'integer'],
            [['email'], 'email'],
            [['email'], 'unique', 'on' => self::SCENARIO_DEFAULT],
            [['name'], 'string', 'min' => 3],
            [['password'], 'string', 'min' => 8],
            [['password'], 'compare', 'on' => self::SCENARIO_DEFAULT],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            // 'id' => 'ID',
            'dt_registration' => 'Дата регистрации',
            'email' => 'Email',
            'name' => 'Ваше имя',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'city_id' => 'Город',
            'is_performer' => 'я собираюсь откликаться на заказы'
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CityQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Contacts]].
     *
     * @return \yii\db\ActiveQuery|ContactQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery|ResponseQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery|ReviewQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['client_id' => 'id']);
    }

    /**
     * Gets query for [[UserSettings]].
     *
     * @return \yii\db\ActiveQuery|UserSettingsQuery
     */
    public function getUserSettings()
    {
        return $this->hasMany(UserSettings::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
