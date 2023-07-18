<?php

namespace app\models;

use yii\base\Model;

class ReviewForm extends Model
{
    public $description;
    public $rate;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'rate'], 'required'],
            [['description'], 'string'],
            [['rate'], 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Ваш комментарий',
            'rate' => 'Оценка работы',
        ];
    }

    /**
     * Create review in reviews table in DB
     * 
     * @param Task $task the task that the review will be
     * associated with
     * @return bool true if the new review is successfully saved in the DB
     */
    public function createReview(Task $task): bool
    {
        if ($this->validate()) {
            $review = new Review();
            $review->attributes = $this->attributes;
            $review->client_id = $task->client_id;
            $review->user_id = $task->performer_id;
            $review->task_id = $task->id;

            return $review->save();
        }
    }
}
