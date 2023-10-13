<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Task;
use app\models\Review;

class ReviewForm extends Model
{
    private const MAX_DESCRIPTION_LENGTH = 200;

    public $description;
    public $rate;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'rate'], 'required'],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGTH],
            [['rate'], 'integer', 'min' => Review::LOWEST_RATING, 'max' => Review::HIGHEST_RATING],
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
            $review->description = $this->description;
            $review->rate = $this->rate;
            $review->client_id = $task->client_id;
            $review->user_id = $task->performer_id;
            $review->task_id = $task->id;

            return $review->save();
        }
    }
}
