<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Response;
use app\models\Task;
use app\models\User;

class ResponseForm extends Model
{
    const MIN_PRICE = 0;
    const MAX_COMMENT_LENGTH = 255;

    public $comment;
    public $price;
    public $taskId;
    public $userId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment', 'price'], 'required'],
            [['price'], 'integer'],
            [['price'], 'compare', 'compareValue' => self::MIN_PRICE, 'operator' => '>', 'type' => 'integer', 'message' => 'Значение «Цена» должно быть больше 0'],
            ['comment', 'string', 'max' => self::MAX_COMMENT_LENGTH],
            [['comment', 'price'], 'validateResponseExist', 'skipOnError' => false],
            [['taskId'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['taskId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],

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
            ->where(['task_id' => $this->taskId])
            ->andWhere(['user_id' => $this->userId]);
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
            $response->comment = $this->comment;
            $response->price = $this->price;
            $response->user_id = $this->userId;
            $response->task_id = $this->taskId;

            $task->link('responses', $response);
        }
    }
}
