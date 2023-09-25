<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Status;
use app\models\TaskQuery;
use app\models\Task;

class FilterTasks extends Model
{
    public $remoteWork;
    public $noPerformer;
    public $periodOption;
    public $categoryId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['noPerformer', 'remoteWork'], 'boolean'],
            [['periodOption'], 'number'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['categoryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'categoryId' => 'Категория',
            'remoteWork' => 'Удалённая работа',
            'noPerformer' => 'Без откликов'
        ];
    }

    /**
     * Search tasks with the status equal to new. Additionally, 
     * you can sort out tasks by placement time and without 
     * an assigned one the performer
     *   
     * @return TaskQuery Returns a request(tasks filtered by status, 
     * category, performer, and period) 
     */
    public function getSearchQuery(): TaskQuery
    {
        $query = Task::find();
        $query->where(['status_id' => Status::STATUS_NEW]);
        $query->andFilterWhere(['category_id' => $this->categoryId]);

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
}
