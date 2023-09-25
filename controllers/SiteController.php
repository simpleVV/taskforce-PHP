<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\forms\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            return $this->redirect(['tasks/index']);
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Display homepage.
     *
     * @return string- if user is guest display homepage.
     * If user is login display tasks page
     */
    public function actionIndex(): string
    {
        $this->layout = '//landing';

        return $this->render('//modals/_login-form', ['model' => new LoginForm()]);
    }
}
