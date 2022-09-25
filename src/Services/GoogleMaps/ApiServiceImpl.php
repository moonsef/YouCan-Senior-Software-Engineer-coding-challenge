<?php

namespace YouCan\Services\GoogleMaps;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class ApiServiceImpl implements ApiService
{
    /**
     * Maximum http requests attempt before failing
     * @var int
     */
    private int $max_attempts = 3;


    /**
     * Failed http requests counter
     * @var int
     */
    private int $attempts_count = 0;


    /**
     * GuzzleHttp client
     * @var Client
     */
    private Client $client;


    public function __construct(?Client $client = null)
    {
        if (is_null($client)) {
            $this->client = new Client();
        } else {
            $this->client = $client;
        }
    }


    /**
     * @throws GuzzleException
     */
    public function get(string $endpoint, array $params): array
    {
        try {
            $res = $this->client->request(
                'GET',
                $endpoint,
                [
                    'query' => $params
                ]
            );

            return [$res->getBody()->getContents()];

        } catch (GuzzleException $e) {
            if ($this->max_attempts > $this->attempts_count) {
                $this->attempts_count += 1;
                return $this->get($endpoint, $params);
            } else {
                throw $e;
            }
        }
    }
}
