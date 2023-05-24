<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Task;
use app\models\Category;
use app\models\Response;
use yii\data\Pagination;

class TasksController extends Controller
{
    /**
     * Отображает страницу с задачами.
     *
     * @return string - странница с задачами
     */
    public function actionIndex(): string
    {
        $task = new Task();

        $task->load(Yii::$app->request->post());

        $tasksQuery = $task->getSearchQuery();
        $countQuery = clone $tasksQuery;

        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 5]);

        $categories = Category::find()->all();
        $models = $tasksQuery->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'task' => $task,
            'pages' => $pages,
            'categories' => $categories
        ]);
    }

    /**
     * Отображает страницу с выбранной задачей.
     * @param $id - идентификатор выбранной задачи
     * @return string странница с выбранной задачей
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
