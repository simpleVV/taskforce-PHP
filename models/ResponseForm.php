<?php

namespace app\models;

use yii\base\Model;

class ResponseForm extends Model
{
    public $comment;
    public $price;
    public $user_id;
    public $task_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment', 'price'], 'required'],
            [['price'], 'integer'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'integer', 'message' => 'Значение «Цена» должно быть больше 0'],
            ['comment', 'string', 'max' => 255],
            [['comment', 'price'], 'validateResponseExist', 'skipOnError' => false],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment' => 'Ваш комментарий',
            'price' => 'Стоимость',
        ];
    }

    /**
     * Checking for response on this task from the current user
     * 
     * @param string $attribute model attribute
     * @param $params
     * @return void
     */

    public function validateResponseExist($attribute, $params): void
    {
        $response = Response::find()
            ->where(['task_id' => $this->task_id])
            ->andWhere(['user_id' => $this->user_id]);
        if ($response->exists()) {
            $this->addError($attribute, 'Вы уже подали заявку на это задание!');
        }
    }

    /**
     * Create response in responses table in DB
     * 
     * @param $task the task that the response will be
     * associated with
     * @return void
     */
    public function createResponse(Task $task): void
    {
        if ($this->validate()) {
            $response = new Response;
            $response->attributes = $this->attributes;

            $task->link('responses', $response);
        }
    }
}
