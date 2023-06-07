<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\Task;
use app\models\Category;
use app\models\Response;
use yii\data\Pagination;

class TasksController extends SecuredController
{
    const TASKS_PAGE_SIZE = 5;
    /**
     * Display tasks page.
     *
     * @return string - tasks page
     */
    public function actionIndex(): string
    {
        $task = new Task();

        $task->load(Yii::$app->request->post());

        $tasksQuery = $task->getSearchQuery();
        $countQuery = clone $tasksQuery;

        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => Self::TASKS_PAGE_SIZE,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);

        $categories = Category::find()->all();
        $models = $tasksQuery
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'models' => $models,
            'task' => $task,
            'categories' => $categories,
            'pagination' => $pagination,
            'pageSize' => Self::TASKS_PAGE_SIZE
        ]);
    }

    /**
     * Display selected task.
     * 
     * @param $id - id of the selected task
     * @return string taks page
     */
    public function actionView($id): string
    {
        $task = Task::findOne($id);
        $responseQuery = Response::find();
        $responseQuery->where(['task_id' => $id]);
        $responses = $responseQuery->all();

        if (!$task) {
            throw new NotFoundHttpException('Задача с таким ID не найдено');
        }

        return $this->render('view', [
            'model' => $task,
            'responses' => $responses
        ]);
    }
}
