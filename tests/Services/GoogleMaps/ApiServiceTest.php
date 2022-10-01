<?php

namespace YouCan\Tests\Services\GoogleMaps;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use YouCan\Services\GoogleMaps\ApiServiceImpl;

class ApiServiceTest extends TestCase
{
    public function test_api_service_retries_three_times_before_failing_to_connect_to_host()
    {
        $mock = new MockHandler([
            new Response(502, [], ''),
            new Response(502, [], ''),
            new Response(502, [], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $api_service_impl = new ApiServiceImpl($client);


        try {
            $api_service_impl->get('/', []);

        } catch (GuzzleException $ex) {
            $this->assertEquals(3, $api_service_impl->getAttemptsCount());
        }
    }
}
