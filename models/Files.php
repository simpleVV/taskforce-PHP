<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $dt_creation
 * @property string $name
 * @property string $path
 * @property int $task_id
 *
 * @property Task $task
 */
class Files extends ActiveRecord
{
    private const MAX_NAME_LENGTH = 60;
    private const MAX_PATH_LENGTH = 255;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_creation'], 'safe'],
            [['name', 'path', 'task_uid'], 'required'],
            [['task_id'], 'integer'],
            [['name'], 'string', 'max' => self::MAX_NAME_LENGTH],
            [['path'], 'string', 'max' => self::MAX_PATH_LENGTH],
            [['path'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dt_creation' => 'Дата создания',
            'name' => 'Имя',
            'path' => 'Путь',
            'task_uid' => 'Задание',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['uid' => 'task_uid']);
    }

    /**
     * {@inheritdoc}
     * 
     * @return FilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FilesQuery(get_called_class());
    }
}
