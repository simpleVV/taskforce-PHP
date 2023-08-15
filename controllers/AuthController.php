<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use Yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\LoginForm;
use app\models\City;
use app\models\SignupForm;
use app\models\Auth;
use app\models\User;


class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'login-vk' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess']
            ]
        ];
    }

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
     * If user exist in db and has auth record - login user and redirect him on
     * home page.
     * If user is not in db - create him and auth record in db and redirect him
     * on home page
     * 
     * @return Response - the current response object
     */
    public function onAuthSuccess($client): Response
    {
        $attributes = $client->getUserAttributes();
        [
            'id' => $id,
            'email' => $email,
            'screen_name' => $name,
            'city' => $city
        ] = $attributes;

        $city = isset($city['title'])
            ? City::findOne(['name' => $city['title']])
            : City::find()->one();

        $authRecord = Auth::findAuthRecord($client->getId(), $id);

        if (Yii::$app->user->isGuest && $authRecord) {
            $user = $authRecord->user;
            Yii::$app->user->login($user);
        }

        if (isset($email) && User::findByEmail($email)) {
            Yii::$app->getSession()->setFlash('error', [
                Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для связи войдите на сайт использую электронную почту.", ['client' => $client->getTitle()])
            ]);
        }

        if (!$authRecord && !User::findByEmail($email)) {
            $user = new User();
            $transaction = $user->getDb()->beginTransaction();

            if ($user->saveUserFromVk($name, $email, $city->id)) {
                $auth = new Auth();

                if ($auth->saveAuthRecord($client->getId(), $id, $user->id)) {
                    $transaction->commit();

                    Yii::$app->user->login($user);
                } else {
                    print_r($auth->getErrors());
                }
            } else {
                print_r($user->getErrors());
            }
        }

        return $this->goHome();
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
     * Display the user registration page|home page if user successfully
     * registered
     * 
     * @return string|Response user registration page|the current response
     *  object.
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
