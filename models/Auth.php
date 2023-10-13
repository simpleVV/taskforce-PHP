<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property string $source
 * @property string $source_id
 * @property int $user_id
 *
 * @property User $user
 */
class Auth extends \yii\db\ActiveRecord
{
    private const MAX_SOURCE_LENGTH = 255;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source', 'source_id', 'user_id'], 'required'],
            [['user_id', 'source_id'], 'integer'],
            [['source',], 'string', 'max' => self::MAX_SOURCE_LENGTH],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'source' => 'Source',
            'source_id' => 'Source ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Save auth record in DB
     * 
     * @param string $source source name
     * @param int $source_id user source id
     * @param int $user_id user id
     * @return bool true - if the auth record is successfully saved
     */
    public function saveAuthRecord(string $source, int $source_id, int $user_id): bool
    {
        $this->source = $source;
        $this->source_id = $source_id;
        $this->user_id = $user_id;

        return $this->save();
    }

    /**
     * Save auth record in DB
     * 
     * @param string $source source name
     * @param int $id user source id
     * @return ?Auth auth record or null
     */
    public static function findAuthRecord(string $source, int $id): ?Auth
    {
        $query = self::find();

        return $query->where([
            'source' => $source,
            'source_id' => $id,
        ])->one();
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
