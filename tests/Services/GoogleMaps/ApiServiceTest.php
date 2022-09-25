<?php

namespace YouCan\Tests\Services\GoogleMaps;

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use YouCan\Services\GoogleMaps\ApiServiceImpl;

class ApiServiceTest extends TestCase
{
    public function test_api_service_retries_three_times_before_failing_to_connect_to_host()
    {
        $mock = new MockHandler([
            // first request
            // Should succeed
            new Response(200, [], 'Request status OK!'),

            // second request
            // Should succeed because the second attempt is a successful response
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            new Response(200, [], 'Request status OK!'),

            // third request
            // Should succeed because the third attempt is a successful response
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            new Response(200, [], 'Request status OK!'),

            // last request
            // Should fail because the third attempt is an exception
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),

        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $api_service_impl =  new ApiServiceImpl($client);


        // first request
        try {
            $res = $api_service_impl->get('/', []);
            $this->assertEquals('Request status OK!', $res[0]);
        } catch (GuzzleException $e) {
        }


        // second request
        try {
            $res = $api_service_impl->get('/', []);
            $this->assertEquals('Request status OK!', $res[0]);
        } catch (GuzzleException $e) {
        }


        // third request
        try {
            $res = $api_service_impl->get('/', []);
            $this->assertEquals('Request status OK!', $res[0]);
        } catch (GuzzleException $e) {
        }


        // last request
        try {
            $api_service_impl->get('/', []);
        } catch (GuzzleException $e) {
            $this->assertEquals("Error Communicating with Server", $e->getMessage());
        }

    }
}
