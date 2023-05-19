<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User;
use app\models\Category;
use app\models\City;
use app\models\Contact;
use app\models\Task;
use app\models\Review;
use app\models\UserSettings;
use Yii;

class UserController extends Controller
{

    /**
     * Отображает страницу профиля выбранного пользователя.
     * @param $id - идентификатор выбранной пользователя
     * @return string странница профиля пользователя.
     */
    public function actionView($id)
    {
        $user = User::findOne($id);
        $categories = Category::find()->all();
        $contacts = Contact::findOne([
            'user_id' => $id
        ]);

        $reviewsQuery = Review::find();
        $reviewsQuery->where(['user_id' => $id]);
        $reviews = $reviewsQuery->all();

        $activeTasksQuery = Task::find();
        $activeTasksQuery->where(['performer_id' => $id]);
        $status = $activeTasksQuery->all()
            ? "Занят"
            : "Открыт для новых заказов";

        if (!$user) {
            throw new NotFoundHttpException('Пользователь таким ID не найден');
        }

        return $this->render('view', [
            'model' => $user,
            'categories' => $categories,
            'reviews' => $reviews,
            'contacts' => $contacts,
            'status' => $status
        ]);
    }

    /**
     * Отображает страницу регистрации пользователя.
     * @return string странница регистрации пользователя.
     */
    public function actionSignup()
    {
        $user = new User();
        $cities = City::find()->all();


        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());
        }

        if ($user->validate()) {
            $user->password = Yii::$app->security->generatePasswordHash($user->password);

            // $user->save(false);
            // $userSettings->save(false);

            $this->goHome();
        };

        return $this->render('signup', [
            'model' => $user,
            'cities' => $cities
        ]);
    }
}
