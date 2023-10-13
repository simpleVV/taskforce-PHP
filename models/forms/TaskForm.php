<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\City;
use app\models\Task;
use app\models\User;
use app\models\Status;
use app\models\Category;

class TaskForm extends Model
{
    private const MIN_TITLE_LENGTH = 10;
    private const MIN_DESCRIPTION_LENGTH = 30;
    private const MIN_PRICE = 0;

    public $title;
    public $description;
    public $categoryId;
    public $location;
    public $price;
    public $dtExpire;
    public $taskUid;
    public $address;
    public $city;
    public $lat;
    public $long;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'categoryId',], 'required'],
            [['title', 'description'], 'trim'],
            [['title'], 'string', 'min' => Self::MIN_TITLE_LENGTH],
            [['description'], 'string', 'min' => Self::MIN_DESCRIPTION_LENGTH],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['categoryId' => 'id']],
            [['price'], 'integer'],
            [['price'], 'compare', 'compareValue' => self::MIN_PRICE, 'operator' => '>', 'type' => 'integer', 'message' => 'Значение «Бюджет» должно быть больше 0'],
            [['dtExpire'], 'date', 'format' => 'php:Y-m-d'],
            [['dtExpire'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>=', 'message' => 'Срок исполнения не может быть меньше текущей даты'],
            [['lat', 'long', 'city', 'address'], 'safe'],
            [['lat', 'long'], 'number'],
            [['city', 'location'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'categoryId' => 'Категория',
            'location' => 'Локация',
            'price' => 'Бюджет',
            'dtExpire' => 'Срок исполнения',
            'city' => 'Город',
            'lat' => 'Широта',
            'long' => 'Долгота',
        ];
    }

    /**
     * Return task id if task successfully create in DB.
     * 
     * @return ?int task id if the task was added to the database
     */
    public function registerTask(): ?int
    {
        if ($this->validate()) {
            $task = new Task;
            $client = User::findOne(['id' => Yii::$app->user->id]);
            $city = City::findOne(['name' => $this->city]);

            $task->title = $this->title;
            $task->description = $this->description;
            $task->price = $this->price;
            $task->location = $this->location;
            $task->dt_expire = $this->dtExpire;
            $task->category_id = $this->categoryId;
            $task->client_id = Yii::$app->user->id;
            $task->status_id = Status::STATUS_NEW;
            $task->uid = $this->taskUid;
            $task->city_id = isset($city) ? $city->id : $client->city->id;
            $task->lat = $this->lat ? $this->lat : $client->city->lat;
            $task->long = $this->long ? $this->long : $client->city->lon;

            return $task->save(false) ? $task->id : null;
        }

        return false;
    }
}
