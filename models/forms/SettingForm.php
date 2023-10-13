<?php

namespace app\models\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\UserCategory;
use app\models\Category;

class SettingForm extends Model
{
    public $name;
    public $email;
    public $bdDate;
    public $phone;
    public $telegram;
    public $about;
    public $categoryId;
    public $checked;

    private const MAX_NAME_LENGTH = 128;
    private const MAX_TELEGRAM_LENGTH = 64;
    private $userId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name'], 'required'],
            [['bdDate'], 'date', 'format' => 'php:Y-m-d',],
            [['phone'], 'number'],
            [['phone'], 'match', 'pattern' => '/^[+-]?\d{11}$/', 'message' => 'Номер телефона должен состоять из 11 символов'],
            [['telegram'], 'string', 'max' => self::MAX_TELEGRAM_LENGTH],
            [['name'], 'string', 'max' => self::MAX_NAME_LENGTH],
            [['about'], 'string'],
            [['email'], 'email'],
            [['email'], 'validateEmail'],
            [['categoryId'], 'each', 'rule' => [
                'exist',
                'targetClass' => Category::class,
                'skipOnError' => true,
                'targetAttribute' => ['categoryId' => 'id']
            ]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'name' => 'Ваше имя',
            'bdDate' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'about' => 'Информация о себе',
            'categoryId' => 'Выбор специализаций',
        ];
    }

    /**
     * Checks the user's entered email for uniqueness
     * 
     * @param string $attribute model attribute
     * @param $params
     * @return void
     */
    public function validateEmail(string $attribute, $params): void
    {
        $user = User::findOne(['email' => $this->email]);

        if ($user && $user->id !== $this->userId) {
            $this->addError($attribute, 'Указанный Email уже зарегистрирован');
        }
    }

    /**
     * save user settings in DB
     * 
     * @param int $id user id
     * @return bool true if the settings are successfully saved in the database
     */
    public function saveUserSettings(int $id): bool
    {
        $user = User::findOne($id);
        $this->userId = $id;

        if ($this->validate() && $user) {

            if ($this->bdDate) {
                $user->bd_date = $this->bdDate;
            }
            if ($this->phone) {
                $user->phone = $this->phone;
            }
            if ($this->telegram) {
                $user->telegram = $this->telegram;
            }
            if ($this->about) {
                $user->about = $this->about;
            }

            if (!empty($this->categoryId)) {
                UserCategory::deleteAll([
                    'and',
                    'user_id' => $user->id,
                    ['NOT IN', 'category_id', $this->categoryId]
                ]);
            }

            $userCategories = UserCategory::find()
                ->select('category_id')
                ->where(['category_id' => $this->categoryId])
                ->all();

            $userCategoriesId = ArrayHelper::getColumn($userCategories, 'category_id');

            if ($this->categoryId) {
                foreach ($this->categoryId as $category) {
                    if (!in_array($category, $userCategoriesId)) {
                        $userCategory = new UserCategory();
                        $userCategory->category_id = $category;
                        $userCategory->user_id = $user->id;
                        $userCategory->save();
                    }
                }
            }

            return $user->save(false);
        }
        return false;
    }
}
