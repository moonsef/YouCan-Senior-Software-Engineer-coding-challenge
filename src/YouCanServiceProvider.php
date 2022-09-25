<?php

namespace YouCan;

use Illuminate\Support\ServiceProvider;
use YouCan\Services\GoogleMaps\ApiService;
use YouCan\Services\GoogleMaps\ApiServiceImpl;


class YouCanServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApiService::class, fn($app) => new ApiServiceImpl());
    }


}
