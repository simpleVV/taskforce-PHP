<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

use taskforce\Geocoder;

class AjaxController extends Controller
{
    /**
     * By entering the address in the field, the user will receive a list of
     * suitable options.
     * 
     * @param string $address the address entered by the user
     * @return Response address options in json format
     */
    public function actionAutocomplete(string $address): Response
    {
        $geocoder = new Geocoder();
        $city = Yii::$app->user->getIdentity()->city->name;

        return $this->asJson($geocoder->getSimilarAddress($city, $address));
    }
}
