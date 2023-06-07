<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use app\models\User;


class UserController extends SecuredController
{
    /**
     * Display user profile page.
     * 
     * @param $id - id of the selected user
     * @return string user profile page.
     */
    public function actionView($id): string
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь с таким ID не найден');
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }
}
