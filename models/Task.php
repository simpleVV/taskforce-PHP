<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

// use yii\behaviors\BlameableBehavior;

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
 * @property User $client
 * @property File[] $files
 * @property User $performer
 * @property Response[] $responses
 * @property Status $status
 */
class Task extends \yii\db\ActiveRecord
{
    public $remoteWork;
    public $noPerformer;
    public $periodOption;

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
            [['noPerformer', 'remoteWork'], 'boolean'],
            [['periodOption'], 'number'],
            [['price', 'category_id', 'client_id', 'performer_id', 'status_id'], 'integer'],
            [['title', 'location'], 'string', 'max' => 128],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['performer_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
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
            'remoteWork' => 'Удалённая работа',
            'noPerformer' => 'Без откликов'
        ];
    }

    /**
     * Поиск задач у которых статус равен новая. Дополнительно можно 
     * отсоритровать задачи по времени размещения и без назначенного
     * исполнителя  
     * @return TaskQuery - Возвращает запрос(задачи отфильтрованные по статусу, категории, исполнителю и периоду) 
     */
    public function getSearchQuery(): TaskQuery
    {
        $query = self::find();
        $query->where(['status_id' => Status::STATUS_NEW]);

        $query->andFilterWhere(['category_id' => $this->category_id]);

        if ($this->noPerformer) {
            $query->andWhere('performer_id IS NULL');
        }

        if ($this->remoteWork) {
            $query->andWhere('location IS NULL');
        }

        if ($this->periodOption) {
            $query->andWhere('UNIX_TIMESTAMP(tasks.dt_creation) > UNIX_TIMESTAMP() - :period', ['period' => $this->periodOption]);
        }

        return $query->orderBy('dt_creation');
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
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getClient()
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
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
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(User::class, ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery|ResponseQuery
     */
    public function getResponses(IdentityInterface $user = null)
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery|StatusQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
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
}
