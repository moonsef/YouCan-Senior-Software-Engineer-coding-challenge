<?php

namespace YouCan\Services\GoogleMaps;

use YouCan\Entities\LocationCollection;

class FindLocationServiceImp implements FindLocationService
{
    /**
     * Google Maps API KEY (The provided API_KEY won't work die to billing)
     * @see https://developers.google.com/maps/gmp-get-started
     * @var string
     */
    private string $api_key = "AIzaSyBGTLiwaY3h4aAZRRZ18YPut7Qa7lLWB3M";


    /**
     * API service
     * @var ApiService
     */
    private ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    public function searchLocation(string $terms): LocationCollection
    {
        $response = $this->apiService->get(
            'https://maps.googleapis.com/maps/api/place/textsearch/json',
            ['query' => $terms, 'key' => $this->api_key]
        );

        $results = json_decode($response[0], true);
        return LocationCollection::createFromArray($results['results']);
    }
}