<?php

namespace app\models;

use Yii;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $dt_registration
 * @property string $email
 * @property string $name
 * @property string $password
 * @property int $city_id
 * @property string|null $bd_date
 * @property string|null $avatar_path
 * @property string|null $about
 * @property int|null $is_performer
 * @property int|null $hide_contacts
 * @property int|null $hide_profile
 * @property string|null $phone
 * @property string|null $telegram
 *
 * @property City $city
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Task[] $tasks
 * @property Task[] $clientTasks
 * @property UserCategory[] $userCategories
 */
class User extends BaseUser implements IdentityInterface
{
    public $old_password;
    public $new_password;
    public $new_password_repeat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class'     => SaveRelationsBehavior::class,
                'relations' => [
                    'categories'
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'city_id'], 'required'],
            [['password'], 'required', 'on' => 'register'],
            [['dt_registration', 'bd_date', 'categories', 'old_password', 'new_password', 'new_password_repeat'], 'safe'],
            // [['avatarFile'], 'file', 'mimeTypes' => ['image/jpeg', 'image/png'], 'extensions' => ['png', 'jpg', 'jpeg']],
            [['password'], 'compare', 'on' => 'register'],
            [['new_password'], 'compare', 'on' => 'update'],
            [['bd_date'], 'date', 'format' => 'php:Y-m-d',],
            [['hide_contacts', 'hide_profile'], 'boolean'],
            [['phone'], 'match', 'pattern' => '/^[+-]?\d{11}$/', 'message' => 'Номер телефона должен состоять из 11 символов'],
            [['name'], 'string', 'max' => 128],
            [['password'], 'string', 'min' => 8],
            [['avatar_path'], 'string', 'max' => 255],
            [['telegram'], 'string', 'max' => 64],
            [['about'], 'string'],
            [['phone'], 'number'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_registration' => 'Дата регистрации',
            'email' => 'Email',
            'name' => 'Имя',
            'password' => 'Пароль',
            'old_password' => 'Старый пароль',
            'new_password' => 'Новый пароль',
            'city_id' => 'Город',
            'bd_date' => 'Дата рождения',
            'avatar_path' => 'Avatar Path',
            'categories' => 'Выбранные категории',
            'about' => 'Информация о себе',
            'hide_contacts' => 'Показывать контакты только заказчику',
            'hide_profile' => 'Hide Profile',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
        ];
    }

    /**
     * Checks the user for active tasks
     * 
     * @return bool - true - if user has active tasks
     */
    public function haveActiveTask(): bool
    {
        return $this->getTasks()
            ->joinWith('status', true, 'INNER JOIN')
            ->where(['statuses.id' => Status::STATUS_IN_PROGRESS])->exists();
    }

    /**
     * Finde user by email in DB
     * 
     * @param string $email by which the database is searched
     * @return ?User user data if the user is found
     * and null if not
     */
    public static function findByEmail(string $email): ?User
    {
        return User::find()
            ->where(['email' => $email])
            ->one();
    }

    /**
     * increase user fail tasks count by 1
     * 
     * @return void
     */
    public function increaseFailTasks(): void
    {
        $this->updateCounters(['fail_tasks' => 1]);
    }
    //сумма всех оценок из отзывов / (кол-во отзывов + счетчик проваленных заданий)
    public function getRating()
    {
        $rating = null;
        $reviewsCount = $this->getReviews()
            ->count();

        if ($reviewsCount) {
            $rate = $this->getReviews()->sum('rate');
            $rating = round($rate / ($reviewsCount + $this->fail_tasks), 2);
        }
        return $rating;
    }

    public function getCompleteTasks()
    {
        return $this->getTasks()
            ->where(['status_id' => Status::STATUS_COMPLETE])
            ->count();
    }

    public function getPosition()
    {
        $query = $this->getReviews();
        $totalCount = $query->count();
        $position = 0;

        if ($query->exists()) {
            $highestRating = $query
                ->where(['rate' => Review::HIGHEST_RATING])
                ->count() * Review::HIGHEST_RATING;
            $goodRating = $query
                ->where(['rate' => Review::GOOD_RATING])
                ->count() * Review::GOOD_RATING;
            $averageRating = $query
                ->where(['rate' => Review::AVERAGE_RATING])
                ->count() * Review::AVERAGE_RATING;
            $poorRating = $query
                ->where(['rate' => Review::POOR_RATING])
                ->count() * Review::POOR_RATING;
            $lowestRating = $query
                ->where(['rate' => Review::LOWEST_RATING])
                ->count() * Review::LOWEST_RATING;

            $position = ($highestRating + $goodRating + $averageRating + $poorRating + $lowestRating) / $totalCount;
        }

        return $position;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('user_categories', ['user_id' => 'id']);
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
        return $this->hasMany(Task::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getClientTasks()
    {
        return $this->hasMany(Task::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery|UserCategoryQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::class, ['user_id' => 'id']);
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
