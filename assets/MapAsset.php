<?php

namespace app\assets;

use yii\web\AssetBundle;

// use taskforce\Geocoder;

class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function init()
    {
        parent::init();

        $this->js[] = "https://api-maps.yandex.ru/2.1/?apikey={$_ENV['YA_API_KEY']}&lang=ru_RU";

        $this->js[] = 'js/map.js';
    }

    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
