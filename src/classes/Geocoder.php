<?php

namespace taskforce;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Psr7\Request;

class Geocoder
{
    // public const API_KEY = '26dbc710-0877-4d4d-bcf6-f2e43dae3ee9';

    /**
     * Finds a list of suitable addresses.
     * 
     * @param string $address - task category id
     * @return array array of suitable addresses
     */
    public function getSimilarAddress(string $address): array
    {
        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru'
        ]);

        $keys = [
            'options' => 'response.GeoObjectCollection.featureMember',
            'coordinates' => 'GeoObject.Point.pos',
            'address' => 'GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted',
            'components' => 'GeoObject.metaDataProperty.GeocoderMetaData.Address.Components',
        ];

        $query =   [
            'apikey' => $_ENV['YA_API_KEY'],
            'geocode' => $address,
            'kind' => 'house',
            'format' => 'json',
            'results' => '5'
        ];
        $result = [];

        try {
            $request = new Request('GET', '1.x/');
            $response = $client->send($request, ['query' => $query]);

            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException("Response error: " . $response->getReasonPhrase(), $request, $response);
            }

            $content = $response->getBody()->getContents();
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException("Invalid json format", $request, $response);
            }

            $options = ArrayHelper::getValue($data, $keys['options']);
            $city = '';
            $street = '';
            $house = '';

            foreach ($options as $option) {
                $latLong = explode(' ', ArrayHelper::getValue($option, $keys['coordinates']));
                $address = ArrayHelper::getValue($option, $keys['address']);
                $components = ArrayHelper::getValue($option, $keys['components']);

                foreach ($components as $component) {
                    if (in_array('locality', $component)) {
                        $city = $component['name'];
                    }

                    if (in_array('street', $component)) {
                        $street = $component['name'];
                    }

                    if (in_array('house', $component)) {
                        $house = $component['name'];
                    }
                }

                $fullAddress = $city . ' ' . $street . ' ' . $house;
                $result[] = [
                    'autocomplete' => $fullAddress,
                    'address' => $fullAddress,
                    'lat' => $latLong['1'],
                    'long' => $latLong['0'],
                    'city' => $city,
                ];
            }
        } catch (RequestException $e) {
            $result = [];
        }

        return $result;
    }
}
