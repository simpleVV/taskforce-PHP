<?php

namespace app\assets;


use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle
{
    public $sourcePath = '@vendor/enyo/dropzone';

    public $css = [
        'dist/basic.css',
        'dist/dropzone.css',
    ];
    public $js = [
        'dist/min/dropzone.min.js',
    ];
}
