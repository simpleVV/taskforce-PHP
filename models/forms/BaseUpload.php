<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

abstract class BaseUpload extends Model
{
    public $file;
    public $files;
    public $size;
    public $fileName;
    public $filePath;

    /**
     * Save file in DB
     * 
     * @param UploadedFile - information for an uploaded file.
     * @return bool if the file is saved successfully
     */
    abstract public function upload(UploadedFile $file): bool;

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
    protected function saveFileInFolder(): bool
    {
        return $this->file->saveAs($this->getFolder() . $this->fileName);
    }

    /**
     * Get the path of the saved file
     *
     * @return string saved file path
     */
    protected function getNewFilePath(): string
    {
        return '/' . $this->getFolder() . $this->fileName;
    }

    /**
     * Checks the existence of the file
     *
     * @return bool truu - if file exist
     */
    protected function fileExists(): bool
    {
        return file_exists($this->getFolder() . $this->file);
    }

    /**
     * Generate new file name
     *
     * @return string new file name
     */
    protected function generateNewFilename(): string
    {
        return uniqid() . '.' . $this->file->extension;
    }

    /**
     * Get the path to the folder for saving files
     *
     * @return string folder path
     */
    protected function getFolder(): string
    {
        return Yii::getAlias('@web') . 'uploads/';
    }
}
