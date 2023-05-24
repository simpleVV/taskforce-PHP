<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\models\User;
use app\models\Category;
use app\models\City;
use app\models\Task;
use app\models\Review;
use Yii;

class UserController extends Controller
{

    /**
     * Отображает страницу профиля выбранного пользователя.
     * @param $id - идентификатор выбранной пользователя
     * @return string странница профиля пользователя.
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

    /**
     * Отображает страницу регистрации пользователя.
     * @return string странница регистрации пользователя.
     */
    public function actionSignup(): string
    {
        $user = new User(['scenario' => 'register']);
        $cities = City::find()->all();


        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($user);
            }

            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);

                // $user->save(false);

                $this->goHome();
            };
        }


        return $this->render('signup', [
            'model' => $user,
            'cities' => $cities
        ]);
    }
}
