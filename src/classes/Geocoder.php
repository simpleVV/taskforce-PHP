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
    private const GEOCODER_URL = [
        'base_uri' => 'https://geocode-maps.yandex.ru'
    ];
    private const KEYS = [
        'options' => 'response.GeoObjectCollection.featureMember',
        'coordinates' => 'GeoObject.Point.pos',
        'components' => 'GeoObject.metaDataProperty.GeocoderMetaData.Address.Components',
    ];

    /**
     * Finds a list of suitable addresses.
     * 
     * @param string $city the user's city specified in the profile
     * @param string $address the address entered by the user
     * @return array array of suitable addresses
     */
    public function getSimilarAddress(string $city, string $address): array
    {
        $client = new Client(self::GEOCODER_URL);
        $result = [];

        $query =   [
            'apikey' => $_ENV['YA_API_KEY'],
            'geocode' => "{$city}+{$address}",
            'kind' => 'house',
            'format' => 'json',
            'results' => '5'
        ];

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

            $options = ArrayHelper::getValue($data, self::KEYS['options']);
            $street = '';
            $house = '';

            foreach ($options as $option) {
                $latLong = explode(' ', ArrayHelper::getValue($option, self::KEYS['coordinates']));
                $components = ArrayHelper::getValue($option, self::KEYS['components']);

                foreach ($components as $component) {
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
