<?php

namespace app\controllers;

use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

abstract class SecuredController extends Controller
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
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
        // 'verbs' => [
        //     'class' => VerbFilter::class,
        //     'actions' => [
        //         'logout' => ['get', 'post'],
        //     ],
        // ],
    }

    /**
     * @param $id
     * @param $modelClass
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findOrDie($id, $modelClass): ActiveRecord
    {
        $reply = $modelClass::findOne($id);

        if (!$reply) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $reply;
    }
}
