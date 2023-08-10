<?php

namespace app\assets;

use taskforce\Geocoder;
use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'https://api-maps.yandex.ru/2.1/?apikey=' . Geocoder::API_KEY . '&lang=ru_RU',
        'js/map.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
