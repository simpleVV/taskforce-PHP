<?php

namespace app\controllers;

use Yii;
use yii\widgets\ActiveForm;
use app\controllers\SecuredController;
use app\models\Response;
use app\models\Task;
use app\models\forms\ResponseForm;
use app\models\Status;

class ResponsesController extends SecuredController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $rules = parent::behaviors();

        $rulePerformer = [
            'allow' => false,
            'actions' => ['deny', 'accept'],
            'matchCallback' => function ($rule, $action) {
                $isPerformer = Yii::$app->user->getIdentity()->is_performer;
                return empty($isPerformer) ? false : true;
            }
        ];

        $ruleClient = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {
                $isPerformer = Yii::$app->user->getIdentity()->is_performer;
                return empty($isPerformer) ? true : false;
            }
        ];

        array_unshift($rules['access']['rules'], $rulePerformer);
        array_unshift($rules['access']['rules'], $ruleClient);

        return $rules;
    }

    /**
     * Display selected task.
     * 
     * @param int $id id of the selected response
     * @return \Yii\web\Response the current response object
     */
    public function actionAccept(int $id): \Yii\web\Response
    {
        $response = $this->findOrDie($id, Response::class);
        $task = $this->findOrDie($response->task_id, Task::class);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $response->approvedResponse();
            $task->performer_id = $response->user_id;
            $task->status_id = Status::STATUS_IN_PROGRESS;

            $response->save(false);
            $task->save(false);

            $transaction->commit();
        } catch (\Exception $error) {
            $transaction->rollBack();
            throw $error;
        }

        return $this->redirect(['tasks/view', 'id' => $response->task_id]);
    }

    /**
     * Deny the user's response to the task and redirect to current task page.
     * 
     * @param int $id id of the selected response
     * @return \Yii\web\Response the current response object
     */
    public function actionDeny(int $id): \Yii\web\Response
    {
        $response = $this->findOrDie($id, Response::class);

        $response->denyResponse();
        $response->save(false);

        return $this->redirect(['tasks/view', 'id' => $response->task_id]);
    }

    /**
     * Create response on current task and redirect to current task page 
     * 
     * @param int $taskId id of the current task
     * @param int $userId id of the current user
     * @return \Yii\web\Response the current response object
     */
    public function actionCreate(int $taskId, int $userId)
    {
        $task = $this->findOrDie($taskId, Task::class);

        if (Yii::$app->request->isPost) {

            $responseForm = new ResponseForm();

            $responseForm->load(Yii::$app->request->post());

            $responseForm->taskId = $taskId;
            $responseForm->userId = $userId;
            $responseForm->createResponse($task);

            return $this->redirect(['tasks/view', 'id' => $task->id]);
        }
    }

    /**
     * Validate the response creation form
     * 
     * @param int $taskId id of the current task
     * @param int $userId id of the current user
     * @return array the error message array indexed by the attribute IDs
     */
    public function actionValidate(int $taskId, int $userId)
    {
        $request = Yii::$app->getRequest();
        $responseForm = new ResponseForm();

        $responseForm->taskId = $taskId;
        $responseForm->userId = $userId;

        if ($request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            if ($responseForm->load($request->post())) {
                return ActiveForm::validate($responseForm);
            }
        }
    }
}
