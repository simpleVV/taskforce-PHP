<?php

namespace app\controllers;

use Yii;
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
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена'), 404);
        }

        return $reply;
    }
}
