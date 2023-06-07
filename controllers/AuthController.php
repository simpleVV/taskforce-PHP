<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use Yii\web\Response;
use yii\bootstrap5\ActiveForm;
use app\models\LoginForm;
use app\models\User;
use app\models\City;
use app\models\SignupForm;

class AuthController extends Controller
{
    /**
     * Redirects the user to the tasks page if 
     * he has successfully logged in or if user already login
     * 
     * @return array|Response - array — the error message indexed by the
     * attribute ID|the current response object.
     */
    public function actionLogin(): array|Response
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->login()) {
                return $this->goHome();
            }
        }
    }

    /**
     * Logout user and redirect him on home page  
     * 
     * @return Response - the current response object
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Display the user registration page|home page.
     * 
     * @return string|Response user registration page|the current response object.
     */
    public function actionSignup(): string|Response
    {
        $signupForm = new SignupForm(['scenario' => 'register']);
        $cities = City::find()->all();


        if (Yii::$app->request->getIsPost()) {
            $signupForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($signupForm);
            }

            if ($signupForm->signup()) {
                $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $signupForm,
            'cities' => $cities
        ]);
    }
}
