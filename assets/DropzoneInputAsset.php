<?php

namespace app\assets;

use yii\web\AssetBundle;

class DropzoneInputAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/dropzone.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
