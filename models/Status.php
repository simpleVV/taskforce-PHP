<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "statuses".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @property Task[] $tasks
 */
class Status extends ActiveRecord
{

    public const STATUS_NEW = 1;
    public const STATUS_CANCEL = 2;
    public const STATUS_IN_PROGRESS = 3;
    public const STATUS_COMPLETE = 4;
    public const STATUS_FAILED = 5;
    public const STATUS_OVERDUE = 6;
    private const MAX_NAME_LENGTH = 60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name', 'code'], 'string', 'max' => self::MAX_NAME_LENGTH],
            [['name'], 'unique'],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'code' => 'Код',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['status_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return StatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StatusQuery(get_called_class());
    }
}
