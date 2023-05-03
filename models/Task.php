<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $dt_creation
 * @property string $title
 * @property string $description
 * @property string|null $location
 * @property int|null $price
 * @property string|null $dt_expire
 * @property int $category_id
 * @property int $client_id
 * @property int $performer_id
 * @property int $status_id
 *
 * @property Category $category
 * @property Users $client
 * @property Files[] $files
 * @property Users $performer
 * @property Responses[] $responses
 * @property Statuses $status
 */
class Task extends \yii\db\ActiveRecord
{
    public bool $noPerformer;
    public int $periodOption;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_creation', 'dt_expire'], 'safe'],
            [['title', 'description', 'category_id', 'client_id', 'performer_id', 'status_id'], 'required'],
            [['description'], 'string'],
            [['noPerformer'], 'boolean'],
            [['periodOption'], 'number'],
            [['price', 'category_id', 'client_id', 'performer_id', 'status_id'], 'integer'],
            [['title', 'location'], 'string', 'max' => 128],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['client_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['performer_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statuses::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            // 'id' => 'ID',
            'dt_creation' => 'Дата создания',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'location' => 'Местоположение',
            'price' => 'Цена',
            'dt_expire' => 'Дата окончания',
            'category_id' => 'Категория',
            'client_id' => 'Заказчик',
            'performer_id' => 'Исполнитель',
            'status_id' => 'Статус',
            'noPerformer' => 'Без исполнителя',
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
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getClient()
    {
        return $this->hasOne(Users::class, ['id' => 'client_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|FilesQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::class, ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery|ResponsesQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Responses::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery|StatusesQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Statuses::class, ['id' => 'status_id']);
    }

    public function getStatusCode()
    {
        return $this->status->code;
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }

    /**
     * 
     * @return TaskQuery the active query used by this AR class.
     */
    public function getSearchQuery()
    {
        $query = self::find();
        $query->where(['status_id' => 1]); //1???

        $query->andFilterWhere(['category_id' => $this->category_id]);

        if ($this->noPerformer) {
            $query->andWhere('performer_id IS NULL');
            var_dump($this->noPerformer);
        }


        if ($this->periodOption) {
            $query->andWhere('UNIX_TIMESTAMP(tasks.dt_creation) > UNIX_TIMESTAMP() - :period', ['period' => $this->periodOption]);
        }

        return $query->orderBy('dt_creation');
    }
}
