<?php

namespace app\models;

use yii\web\UploadedFile;

class UploadForm extends BaseUpload
{
    public $task_uid;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['files'], 'file', 'maxFiles' => 4]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'files' => 'Файлы'
        ];
    }

    /**
     * Save file in DB
     * 
     * @param UploadedFile - information for an uploaded file.
     * @return bool if the file is saved successfully
     */
    public function upload(UploadedFile $file): bool
    {
        $this->file = $file;
        $this->size = $file->size;
        $this->fileName = $this->generateNewFilename();
        $this->filePath = $this->getNewFilePath();

        if ($this->saveFileInFolder()) {
            $fileInfo = new Files;
            $fileInfo->name = $this->fileName;
            $fileInfo->path = $this->filePath;
            $fileInfo->size = $this->size;
            $fileInfo->task_uid = $this->task_uid;
        }

        return $fileInfo->save(false);
    }
}
