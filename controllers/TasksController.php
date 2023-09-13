<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Pagination;
use app\models\Task;
use app\models\TaskForm;
use app\models\Category;
use app\models\ResponseForm;
use app\models\ReviewForm;
use app\models\UploadForm;
use app\models\Status;

use taskforce\logic\actions\CancelAction;
use taskforce\logic\actions\RefusalAction;

class TasksController extends SecuredController
{
    const TASKS_PAGE_SIZE = 5;

    /**
     * Display tasks page.
     * 
     * @param ?int $category - task category id
     * @return string tasks page
     */
    public function actionIndex($category = null): string
    {
        $task = new Task();

        if ($category) {
            $task->category_id = $category;
        }
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
     * Display my tasks page.
     * 
     * @param string $status - task status
     * @return string my tasks page
     */
    public function actionViewMy($status): string
    {
        $user = Yii::$app->user->identity;
        $task = new Task();

        $statusId = Status::findOne(['code' => $status])
            ? Status::findOne(['code' => $status])->id
            : null;

        $tasksQuery = $user->is_performer
            ? $task->getPerformerTasks($user->id, $statusId)
            : $task->getClientTasks($user->id, $statusId);

        $models = $tasksQuery->all();

        return $this->render('view-my', [
            'models' => $models,
            'isPerformer' => $user->is_performer
        ]);
    }

    /**
     * Display selected task.
     * 
     * @param int $id id of the selected task
     * @return string taks page
     */
    public function actionView(int $id): string
    {
        $task = Task::findOne($id);
        $responseForm = new ResponseForm();
        $reviewsForm = new ReviewForm();

        if (!$task) {
            throw new NotFoundHttpException('Задача с таким ID не найдено');
        }

        return $this->render('view', [
            'model' => $task,
            'responseForm' => $responseForm,
            'reviewForm' => $reviewsForm
        ]);
    }

    /**
     * Display the task registration form|home page if task successfully
     * registered.
     * 
     * @return string|\Yii\web\Response taks registration page||the current 
     * response object.
     */
    public function actionCreate(): string|\Yii\web\Response
    {
        $taskCreateForm = new TaskForm();
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
     * @return \Yii\web\Response a response that is configured to send $data
     * formatted as JSON.
     */
    public function actionUpload(): \Yii\web\Response
    {
        $uploadFiles = new UploadForm;

        if (Yii::$app->request->isPost) {
            $uploadFiles->files = UploadedFile::getInstancesByName('file');

            if ($uploadFiles->files) {
                $uploadFiles->task_uid = Yii::$app->session->get('task_uid');
                $uploadFiles->uploadFiles();

                return $this->asJson($uploadFiles->getAttributes());
            }
        }
    }

    /**
     * Converts the task status to refused and redirect the user to view the
     * current task page. 
     * 
     * @param int $id id of the selected task
     * @return \Yii\web\Response the current response object.
     */
    public function actionDeny(int $id): \Yii\web\Response
    {
        $task = Task::findOne($id);
        $performer = $task->getPerformer()->one();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $task->setNextStatus(new RefusalAction);
            $performer->increaseFailTasks();
            $transaction->commit();
        } catch (\Exception $error) {
            $transaction->rollBack();
            throw $error;
        }


        return $this->redirect(['tasks/view', 'id' => $id]);
    }

    /**
     * Converts the task status to cancelled and redirect the user to view the
     * current task page. 
     * 
     * @param int $id id of the selected task
     * @return \Yii\web\Response the current response object.
     */
    public function actionCancel(int $id): \Yii\web\Response
    {
        $task = Task::findOne($id);

        $task->setNextStatus(new CancelAction);

        return $this->redirect(['tasks/view', 'id' => $id]);
    }
}
