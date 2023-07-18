<?php

namespace app\controllers;

use Yii;
use yii\widgets\ActiveForm;
use app\controllers\SecuredController;
use app\models\Response;
use app\models\Task;
use app\models\ResponseForm;
use app\models\Status;

class ResponsesController extends SecuredController
{
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
            $response->is_approved = true;
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

        $response->is_deny = true;
        $response->save(false);

        return $this->redirect(['tasks/view', 'id' => $response->task_id]);
    }

    /**
     * Create response on current task and redirect to current task page 
     * 
     * @param int $taskId id of the current task
     * @return \Yii\web\Response the current response object
     */
    public function actionCreate(int $taskId)
    {
        $task = $this->findOrDie($taskId, Task::class);

        if (Yii::$app->request->isPost) {

            $responseForm = new ResponseForm();

            $responseForm->task_id = $taskId;
            $responseForm->user_id = $this->getUser()->getId();
            $responseForm->load(Yii::$app->request->post());
            $responseForm->createResponse($task);

            return $this->redirect(['tasks/view', 'id' => $task->id]);
        }
    }

    /**
     * Validate the response creation form
     * 
     * @param int $$taskId id of the current task
     * @return array the error message array indexed by the attribute IDs
     */
    public function actionValidate(int $taskId)
    {
        $request = Yii::$app->getRequest();
        $responseForm = new ResponseForm();

        $responseForm->task_id = $taskId;
        $responseForm->user_id = $this->getUser()->getId();

        if ($request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            if ($responseForm->load($request->post())) {
                return ActiveForm::validate($responseForm);
            }
        }
    }
}
