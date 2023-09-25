<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\models\forms\AvatarUpload;
use app\models\forms\SettingForm;
use app\models\forms\SecuritySettingForm;
use app\models\Category;
use app\models\User;

class UserController extends SecuredController
{
    /**
     * Display user profile page.
     * 
     * @param int $id id of the selected user
     * @return string user profile page.
     */
    public function actionView(int $id): string
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь с таким ID не найден');
        }

        return $this->render('view', [
            'model' => $user,
        ]);
    }

    /**
     * Display user settings page.
     * 
     * @param int $id user id
     * @return string|\Yii\web\Response user settings page.
     */
    public function actionSettings(int $id): string|\Yii\web\Response
    {
        $settingForm = new SettingForm();
        $avatar = new AvatarUpload();
        $categories = Category::find()->all();
        $user = User::findOne($id);
        $settingForm->categoryId = ArrayHelper::getColumn($user->categories, 'id');

        if (Yii::$app->request->getIsPost()) {
            $avatarImage = UploadedFile::getInstance($avatar, 'file');

            if ($avatarImage) {
                $avatar->upload($avatarImage);
            }

            $settingForm->load(Yii::$app->request->post());

            if ($settingForm->saveUserSettings($id)) {
                return $this->redirect(['user/view', 'id' => $id]);
            }
        }

        return $this->render('settings', [
            'avatar' => $avatar,
            'model' => $settingForm,
            'categories' => $categories,
        ]);
    }

    /**
     * Display user security settings page.
     * 
     * @param int $id user id
     * @return string|\Yii\web\Response user security settings page.
     */
    public function actionSecuritySettings(int $id): string|\Yii\web\Response
    {
        $securitySettingForm = new SecuritySettingForm();

        if (Yii::$app->request->getIsPost()) {
            $securitySettingForm->load(Yii::$app->request->post());

            if ($securitySettingForm->saveUserSettings($id)) {
                return $this->redirect(['user/view', 'id' => $id]);
            }
        }

        return $this->render('security-settings', [
            'model' => $securitySettingForm
        ]);
    }
}
