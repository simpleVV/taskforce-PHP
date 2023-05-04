<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Task;
use app\models\Category;
use yii\data\Pagination;

class TasksController extends Controller
{
    /**
     * Отображает страницу с задачами.
     *
     * @return string
     */
    public function actionIndex()
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

    // public function actionView($id)
    // {
    //     $task = Task::findOne($id);

    //     if ($task === null) {
    //         throw new NotFoundHttpException;
    //     }

    //     return $this->render('task', [
    //         'task' => $task,
    //     ]);
    // }

    // public function actionCreate()
    // {
    //     $data = [];

    //     $newTask = new Task;
    //     $newTask->load($data);

    //     $newTask->setScenario('insert');
    //     $isValid = $newTask->validate();

    //     if ($isValid) {
    //         $newTask->save();
    //     }
    // }
}
