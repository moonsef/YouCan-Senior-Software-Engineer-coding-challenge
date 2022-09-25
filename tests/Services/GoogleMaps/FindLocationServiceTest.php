<?php

namespace YouCan\Tests\Services\GoogleMaps;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use YouCan\Services\GoogleMaps\ApiServiceImpl;
use YouCan\Services\GoogleMaps\FindLocationServiceImp;

class FindLocationServiceTest extends TestCase
{
    public function test_search_location_return_location_collection()
    {
        $google_place_api_fake_response = array(
            "html_attributions" => array(),
            "results" => array(
                array(
                    "formatted_address" => "Main St, Denver, CO 80238, USA",
                    "geometry" => array(
                        "location" => array(
                            "lat" => 39.782267,
                            "lng" => -104.8919341
                        ),
                        "viewport" => array(
                            "northeast" => array(
                                "lat" => 39.78361682989273,
                                "lng" => -104.8905842701073
                            ),
                            "southwest" => array(
                                "lat" => 39.78091717010729,
                                "lng" => -104.8932839298927
                            )
                        )
                    ),
                    "icon" => "https://maps.gstatic.com/mapfiles/place_api/icons/v1/png_71/geocode-71.png",
                    "icon_background_color" => "#7B9EB0",
                    "icon_mask_base_uri" => "https://maps.gstatic.com/mapfiles/place_api/icons/v2/generic_pinlet",
                    "name" => "Main St",
                    "place_id" => "ChIJIS85_gd7bIcRJIGEPue1cJI",
                    "reference" => "ChIJIS85_gd7bIcRJIGEPue1cJI",
                    "types" => array(
                        "route"
                    )
                )
            ),
            "status" => "OK"
        );

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode($google_place_api_fake_response)),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $api_service_impl = new ApiServiceImpl($client);
        $find_location_service = new FindLocationServiceImp($api_service_impl);
        $response = $find_location_service->searchLocation("foo");

        $this->assertCount(1, $response);

        $this->assertEquals($google_place_api_fake_response['results'][0]['formatted_address'], $response[0]->getAddress());
        $this->assertEquals($google_place_api_fake_response['results'][0]['place_id'], $response[0]->getPlaceID());
        $this->assertEquals($google_place_api_fake_response['results'][0]['geometry']['location']['lat'], $response[0]->getLat());
        $this->assertEquals($google_place_api_fake_response['results'][0]['geometry']['location']['lng'], $response[0]->getLng());

    }
}
