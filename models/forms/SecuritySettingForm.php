<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class SecuritySettingForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $confirmNewPassword;
    public $hideContacts;

    private $_user;
    private const MAX_PASS_LENGTH = 8;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'confirmNewPassword'], 'safe'],
            [['oldPassword'], 'validatePassword'],
            [['newPassword'], 'string', 'min' => self::MAX_PASS_LENGTH],
            ['oldPassword', 'required', 'when' => function ($model) {
                return !empty($model->newPassword);
            }, 'whenClient' => "function (attribute, value) {
                return $('#securitysettingform-newpassword').val() !== '';
            }"],
            ['newPassword', 'required', 'when' => function ($model) {
                return !empty($model->oldPassword);
            }, 'whenClient' => "function (attribute, value) {
                return $('#securitysettingform-oldpassword').val() !== '';
            }"],
            ['confirmNewPassword', 'required', 'when' => function ($model) {
                return !empty($model->newPassword);
            }, 'whenClient' => "function (attribute, value) {
                return $('#securitysettingform-newpassword').val() !== '';
            }"],
            [['confirmNewPassword'], 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли не совпадают'],
            [['hideContacts'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Текущий пароль',
            'newPassword' => 'Новый пароль',
            'confirmNewPassword' => 'Подтверждение нового пароля',
            'hideContacts' => 'Показывать контакты только заказчику',
        ];
    }

    /**
     * Checking the user's entered password for a 
     * match with the password from the database
     * 
     * @param string $attribute model attribute
     * @param $params
     * @return void
     */
    public function validatePassword(string $attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->_user;

            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError($attribute, 'Старый пароль указан неверно!');
            }
        }
    }

    /**
     * save user settings in DB
     * 
     * @param int $id user id
     * @return bool true if the settings are successfully saved in the database
     */
    public function saveUserSettings(int $id): bool
    {
        $user = $this->getUser($id);

        if ($this->validate() && $user) {
            if ($this->newPassword) {
                $user->password = Yii::$app->security->generatePasswordHash($this->newPassword);
            }

            $user->hide_contacts = $this->hideContacts;

            return $user->save(false);
        }
        return false;
    }

    /**
     * Get user record in the database by id
     * 
     * @param int $id 
     * @return ?User user records if there is one in the database
     * or null
     */
    private function getUser(int $id): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($id);
        }

        return $this->_user;
    }
}
