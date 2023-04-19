<?php

namespace app\controllers;

use app\models\Tasks;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Tasks::find()
            ->where(['status_id' => 1])
            ->orderBy('dt_creation')
            ->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

    public function actionView($id)
    {
        $task = Tasks::findOne($id);

        if ($task === null) {
            throw new NotFoundHttpException;
        }

        return $this->render('task', [
            'task' => $task,
        ]);
    }

    public function actionCreate()
    {
        $data = [];

        $newTask = new Tasks;
        $newTask->load($data);

        $newTask->setScenario('insert');
        $isValid = $newTask->validate();

        if ($isValid) {
            $newTask->save();
        }
    }
}
