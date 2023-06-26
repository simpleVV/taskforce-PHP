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
            [['name'], 'string', 'max' => 60],
            [['path'], 'string', 'max' => 255],
            [['path'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_creation' => 'Дата создания',
            'name' => 'Имя',
            'path' => 'Путь',
            'task_uid' => 'Задание',
        ];
    }

    /**
     * Save file info in DB
     * 
     * @return bool - true if the file info is successfully saved in the DB
     */
    public function create()
    {
        return $this->save(false);
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
