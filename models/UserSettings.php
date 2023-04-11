<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $id
 * @property string|null $date_birth
 * @property string|null $avatar
 * @property string|null $about
 * @property int $category_id
 * @property int $contacts_id
 * @property int $user_id
 * @property int|null $hide_contacts
 *
 * @property Category $category
 * @property Contacts $contacts
 * @property Users $user
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_birth'], 'safe'],
            [['about'], 'string'],
            [['category_id', 'contacts_id', 'user_id'], 'required'],
            [['category_id', 'contacts_id', 'user_id', 'hide_contacts'], 'integer'],
            [['avatar'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['contacts_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::class, 'targetAttribute' => ['contacts_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            // 'id' => 'ID',
            'date_birth' => 'Дата рождения',
            'avatar' => 'Аватар',
            'about' => 'О себе',
            // 'category_id' => 'Category ID',
            // 'contacts_id' => 'Contacts ID',
            // 'user_id' => 'User ID',
            'hide_contacts' => 'Скрыть контакты',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Contacts]].
     *
     * @return \yii\db\ActiveQuery|ContactsQuery
     */
    public function getContacts()
    {
        return $this->hasOne(Contacts::class, ['id' => 'contacts_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserSettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSettingsQuery(get_called_class());
    }
}
