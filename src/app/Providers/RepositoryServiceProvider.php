<?php

namespace App\Providers;

use App\Interfaces\GraphInterface;
use App\Interfaces\WikiUrlInterface;
use App\Repositories\GraphRepository;
use App\Repositories\WikiUrlRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(GraphInterface::class, GraphRepository::class);
        $this->app->bind(WikiUrlInterface::class, WikiUrlRepository::class);
    }
}
