<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;

use taskforce\Geocoder;

class AjaxController extends Controller
{
    /**
     * By entering the address in the field, the user will receive a list of
     * suitable options.
     * 
     * @param string $address - task category id
     * @return Response address options in json format
     */
    public function actionAutocomplete(string $address): Response
    {
        $geocoder = new Geocoder();

        return $this->asJson($geocoder->getSimilarAddress($address));
    }
}
