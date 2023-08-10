<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TaskForm extends Model
{
    const MIN_TITLE_LENGTH = 10;
    const MIN_DESCRIPTION_LENGTH = 30;

    public $title;
    public $description;
    public $category_id;
    public $location;
    public $price;
    public $dt_expire;
    public $task_uid;
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
            [['title', 'description', 'category_id',], 'required'],
            [['title', 'description'], 'trim'],
            [['title'], 'string', 'min' => Self::MIN_TITLE_LENGTH],
            [['description'], 'string', 'min' => Self::MIN_DESCRIPTION_LENGTH],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['price'], 'integer'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'integer', 'message' => 'Значение «Бюджет» должно быть больше 0'],
            [['dt_expire'], 'date', 'format' => 'php:Y-m-d'],
            [['dt_expire'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>=', 'message' => 'Срок исполнения не может быть меньше текущей даты'],
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
            'category_id' => 'Категория',
            'location' => 'Локация',
            'price' => 'Бюджет',
            'dt_expire' => 'Срок исполнения',
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
            $city = City::findOne(['name' => $this->city]);
            $client = USer::findOne(['id' => Yii::$app->user->id]);

            $task->attributes = $this->attributes;
            $task->client_id = Yii::$app->user->id;
            $task->status_id = Status::STATUS_NEW;
            $task->uid = $this->task_uid;
            $task->city_id = $city ? $city->id : $client->city->id;
            $task->lat = $this->lat ? $this->lat : $client->city->lat;
            $task->long = $this->long ? $this->long : $client->city->lon;

            return $task->save(false) ? $task->id : null;
        }
    }
}
