<?php

namespace app\controllers;

use Yii;
use yii\widgets\ActiveForm;
use app\controllers\SecuredController;
use app\models\Task;
use app\models\forms\ReviewForm;

use taskforce\logic\actions\CompleteAction;

class ReviewsController extends SecuredController
{
    /**
     * Create review on current task and redirect to current task page 
     * 
     * @param int $taskId id of the current task
     * @return \Yii\web\Response the current response object
     */
    public function actionCreate(int $taskId): \Yii\web\Response
    {
        $task = $this->findOrDie($taskId, Task::class);

        if (Yii::$app->request->isPost) {

            $reviewForm = new ReviewForm();
            $reviewForm->load(Yii::$app->request->post());

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $reviewForm->createReview($task);
                $task->setNextStatus(new CompleteAction);
                $transaction->commit();
            } catch (\Exception $error) {
                $transaction->rollBack();
                throw $error;
            }

            return $this->redirect(['tasks/view', 'id' => $task->id]);
        }
    }

    /**
     * Validate the review creation form
     * 
     * @param int $$taskId  id of the current task
     * @return array the error message array indexed by the attribute IDs
     */
    public function actionValidate(): array
    {
        $request = Yii::$app->getRequest();
        $reviewForm = new ReviewForm();

        if ($request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            if ($reviewForm->load($request->post())) {
                return ActiveForm::validate($reviewForm);
            }
        }
    }
}
