<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public $file;
    public $files;
    public $size;
    public $fileName;
    public $filePath;
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

    /**
     * Saves all files in DB
     *
     * @return bool if the files are saved successfully
     */
    public function uploadFiles(): bool
    {
        $uploadResult = false;

        if ($this->validate()) {
            foreach ($this->files as $file) {
                var_dump($file);
                $uploadResult = $this->upload($file);
            }
            return $uploadResult;
        }
    }

    /**
     * Save file in defaul folder
     *
     * @return bool if file successfully saved
     */
    private function saveFileInFolder(): bool
    {
        return $this->file->saveAs($this->getFolder() . $this->fileName);
    }

    /**
     * Get the path of the saved file
     *
     * @return string saved file path
     */
    private function getNewFilePath(): string
    {
        return '/' . $this->getFolder() . $this->fileName;
    }

    /**
     * Checks the existence of the file
     *
     * @return bool truu - if file exist
     */
    private function fileExists(): bool
    {
        return file_exists($this->getFolder() . $this->file);
    }

    /**
     * Generate new file name
     *
     * @return string new file name
     */
    private function generateNewFilename(): string
    {
        return uniqid() . '.' . $this->file->extension;
    }

    /**
     * Get the path to the folder for saving files
     *
     * @return string folder path
     */
    private function getFolder(): string
    {
        return Yii::getAlias('@web') . 'uploads/';
    }
}
