<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $id
 * @property string|null $date_birth
 * @property string|null $avatar_path
 * @property string|null $about
 * @property int|null $is_performer
 * @property int|null $hide_profile
 * @property int|null $hide_contacts
 * @property int|null $contacts_id
 * @property int $user_id
 *
 * @property Contact $contacts
 * @property User $user
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
            [['is_performer', 'hide_profile', 'hide_contacts', 'contacts_id', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['avatar_path'], 'string', 'max' => 255],
            [['contacts_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::class, 'targetAttribute' => ['contacts_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_birth' => 'День рождения',
            'avatar_path' => '',
            'about' => 'О себе',
            'is_performer' => 'я собираюсь откликаться на заказы',
            'hide_profile' => 'Hide Profile',
            'hide_contacts' => 'Hide Contacts',
            'contacts_id' => 'Contacts ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Contact]].
     *
     * @return \yii\db\ActiveQuery|ContactsQuery
     */
    public function getContacts()
    {
        return $this->hasOne(Contact::class, ['id' => 'contacts_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
