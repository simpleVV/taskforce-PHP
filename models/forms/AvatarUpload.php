<?php

namespace app\models\forms;

use Yii;
use yii\web\UploadedFile;
use app\models\forms\BaseUpload;
use app\models\User;

class AvatarUpload extends BaseUpload
{
    public $userId;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'mimeTypes' => ['image/jpeg', 'image/png'], 'extensions' => ['png', 'jpg', 'jpeg']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Аватар'
        ];
    }

    /**
     * Saves the avatar image in a folder and writes its path to the db
     * 
     * @param UploadedFile - information for an uploaded file.
     * @return bool if the file is saved successfully
     */
    public function upload(UploadedFile $file): bool
    {
        $this->file = $file;
        $this->fileName = $this->generateNewFilename();
        $this->filePath = $this->getNewFilePath();
        $user = $this->getUser();

        if ($this->saveFileInFolder() && $user) {
            $this->_user->avatar_path = $this->filePath;

            return $user->save(false);
        }
    }

    /**
     * Get user record in the database by id
     * 
     * @return ?User - user records if there is one in the database
     * or null
     */
    private function getUser(): ?User
    {
        $userId = Yii::$app->user->identity->id;

        if ($this->_user === null) {
            $this->_user = User::findOne($userId);
        }

        return $this->_user;
    }
}
