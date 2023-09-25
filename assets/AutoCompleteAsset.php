<?php

namespace app\assets;

use yii\web\AssetBundle;

class AutoCompleteAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.min.css',
    ];
    public $js = [
        'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js',
        'js/auto-complete.js',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
    ];
}
