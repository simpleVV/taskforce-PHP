<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use app\models\Task;
use app\models\Category;
use app\models\Status;
use app\models\forms\FilterTasks;
use app\models\forms\ResponseForm;
use app\models\forms\ReviewForm;
use app\models\forms\UploadForm;

use app\models\forms\TaskForm;
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

    public function actionIndex(): string
    {
        $model = new FilterTasks;
        $categories = Category::find()->all();

        if (Yii::$app->request->getIsGet()) {
            $model->load(Yii::$app->request->get());
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getSearchQuery(),
            'pagination' => [
                'pageSize' => self::TASKS_PAGE_SIZE
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'pageSize' => Self::TASKS_PAGE_SIZE,
            'categories' => $categories,
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

        $dataProvider = new ActiveDataProvider([
            'query' => $tasksQuery,
            'pagination' => [
                'pageSize' => self::TASKS_PAGE_SIZE
            ]
        ]);

        return $this->render('view-my', [
            'dataProvider' => $dataProvider,
            'isPerformer' => $user->is_performer,
            'pageSize' => Self::TASKS_PAGE_SIZE,
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
        $taskForm = new TaskForm();
        $categories = Category::find()->all();

        if (!Yii::$app->session->has('task_uid')) {
            Yii::$app->session->set('task_uid', uniqid('upload'));
        }

        if (Yii::$app->request->getIsPost()) {
            $taskForm->load(Yii::$app->request->post());
            $taskForm->taskUid = Yii::$app->session->get('task_uid');
            $taskId = $taskForm->registerTask();

            if ($taskId) {
                Yii::$app->session->remove('task_uid');

                return $this->redirect(['tasks/view', 'id' => $taskId]);
            }
        }

        return $this->render('create', [
            'model' => $taskForm,
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
                $uploadFiles->taskUid = Yii::$app->session->get('task_uid');
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
