<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OpenWeatherApiService
{
    private $apiUrl;
    private $apiKey;

    /**
     * OpenWeatherService constructor.
     */
    public function __construct()
    {
        $this->apiUrl = $_ENV['OPEN_WEATHER_API_URL'];
        $this->apiKey = $_ENV['OPEN_WEATHER_API_KEY'];
    }

    public function apiCall($city, $method = 'GET'): ResponseInterface
    {
        $query = [
            'query' => [
                'q' => $city,
                'APPID' => $this->apiKey,
            ],
        ];

        $httpClient = HttpClient::create();

        return $httpClient->request($method, $this->apiUrl, $query);
    }
}
