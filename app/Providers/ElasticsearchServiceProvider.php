<?php

namespace App\Providers;

use Elasticsearch\Client; 
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([config('elasticsearch.hosts')[0]])
                ->setSSLVerification(config('elasticsearch.ssl_verification'))
                ->build();
        });
    }

    public function boot()
    {
        //
    }
}