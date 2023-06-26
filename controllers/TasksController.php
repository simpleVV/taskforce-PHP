<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Pagination;
use app\models\Task;
use app\models\TaskCreateForm;
use app\models\Category;
use Yii\web\Response;
use app\models\UploadForm;

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
        $responseQuery = \app\models\Response::find();
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

    /**
     * Display the task registration form|home page if task successfully
     * registered.
     * 
     * @return string|Response taks registration page||the current response
     * object.
     */
    public function actionCreate(): string|Response
    {
        $taskCreateForm = new TaskCreateForm();
        $categories = Category::find()->all();

        if (!Yii::$app->session->has('task_uid')) {
            Yii::$app->session->set('task_uid', uniqid('upload'));
        }

        if (Yii::$app->request->getIsPost()) {
            $taskCreateForm->load(Yii::$app->request->post());
            $taskCreateForm->task_uid = Yii::$app->session->get('task_uid');
            $taskId = $taskCreateForm->registerTask();

            if ($taskId) {
                Yii::$app->session->remove('task_uid');

                return $this->redirect(['tasks/view', 'id' => $taskId]);
            }
        }

        return $this->render('create', [
            'model' => $taskCreateForm,
            'categories' => $categories,
        ]);
    }

    /**
     * Upload and save files.
     * 
     * @return Response — a response that is configured to send $data formatted
     * as JSON.
     */
    public function actionUpload(): Response
    {
        if (Yii::$app->request->isPost) {
            $uploadFiles = new UploadForm;
            $uploadFiles->files = UploadedFile::getInstancesByName('file');

            if ($uploadFiles->files) {
                $uploadFiles->task_uid = Yii::$app->session->get('task_uid');
                $uploadFiles->uploadFiles();

                return $this->asJson($uploadFiles->getAttributes());
            }
        }
    }
}
