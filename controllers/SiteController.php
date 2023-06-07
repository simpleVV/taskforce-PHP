<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii\web\Response;
use app\models\LoginForm;

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
     * @return string|Response - if user is guest display homepage.
     * If user is login display tasks page
     */
    public function actionIndex(): string|Response
    {
        $this->layout = '//landing';

        return $this->render('//modals/_login_form', ['model' => new LoginForm()]);
    }
}
