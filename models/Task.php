<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use taskforce\logic\TaskManager;
use taskforce\logic\actions\AbstractAction;

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
 * @property Files[] $files
 * @property User $performer
 * @property Response[] $responses
 * @property Status $status
 * @property City $status
 */
class Task extends ActiveRecord
{
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
            [['status_id'], 'default', 'value' => Status::STATUS_NEW],
            [['dt_creation'], 'safe'],
            [['client_id', 'status_id'], 'required'],
            [['client_id', 'performer_id', 'status_id'], 'integer'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['performer_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
     * Search for the client's tasks by status
     * @param int $user_id client id
     * @param int $status_id status id  
     *   
     * @return Task Returns a request(tasks filtered by status 
     */
    public function getClientTasks(int $user_id, int $status_id): TaskQuery
    {
        $query = self::find();
        $query->where(['client_id' => $user_id]);

        switch ($status_id) {
            case Status::STATUS_NEW:
                $query->andWhere(['status_id' => Status::STATUS_NEW]);
                break;
            case Status::STATUS_IN_PROGRESS:
                $query->andWhere(['status_id' => Status::STATUS_IN_PROGRESS]);
                break;
            case Status::STATUS_CANCEL:
                $query->andWhere(['NOT IN', 'status_id', [
                    Status::STATUS_NEW,
                    Status::STATUS_IN_PROGRESS
                ]]);
        };

        return $query->orderBy('dt_creation');
    }

    /**
     * Search for the performer's tasks by status
     * @param int $user_id performer id
     * @param int $status_id status id  
     *   
     * @return TaskQuery Returns a request(tasks filtered by status 
     */
    public function getPerformerTasks(int $user_id, int $status_id): TaskQuery
    {
        $query = self::find();
        $query->where(['performer_id' => $user_id]);

        switch ($status_id) {
            case Status::STATUS_IN_PROGRESS:
                $query->andWhere(['status_id' => Status::STATUS_IN_PROGRESS]);
                break;
            case Status::STATUS_OVERDUE:
                $query->andWhere(['status_id' => Status::STATUS_IN_PROGRESS]);
                $query->andWhere(['<', 'dt_expire', date('Y-m-d')]);
                break;
            case Status::STATUS_CANCEL:
                $query->andWhere([
                    'status_id' => [
                        Status::STATUS_COMPLETE,
                        Status::STATUS_FAILED,
                    ]
                ]);
                break;
        };

        return $query->orderBy('dt_creation');
    }

    /**
     * Assigns the task the following status after performing the action
     *   
     * @param AbstractAction $action completed action 
     * @return void 
     */
    public function setNextStatus(AbstractAction $action): void
    {
        $taskManager = new TaskManager($this->status->code, $this->client_id, $this->performer_id);
        $newStatus = $taskManager->getNextStatus($action);
        $status = Status::findOne(['code' => $newStatus]);

        $this->link('status', $status);
    }

    /**
     * Returns the actions available for current task and current user
     *   
     * @param IdentityInterface $user_current user 
     * @return array available actions  
     */
    public function getTaskActions(IdentityInterface $user): array
    {
        $userRole = $user->is_performer
            ? TaskManager::ROLE_PERFORMER
            : TaskManager::ROLE_CLIENT;

        $taskManager = new TaskManager($this->status->code, $this->client_id, $this->performer_id);
        $availableActions = $taskManager->getAvailableActions($user->id, $userRole);

        return $availableActions;
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
        return $this->hasMany(Files::class, ['task_uid' => 'uid']);
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
     * @param  ?IdentityInterface $user_current user
     * @return \yii\db\ActiveQuery|ResponseQuery
     */
    public function getResponses(IdentityInterface $user = null): ResponseQuery
    {
        $query = $this->hasMany(Response::class, ['task_id' => 'id']);

        if ($user && !$user->id === $this->client_id) {
            return $query->where(['user_id' => $user->id]);
        }

        return $query;
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
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }
}
