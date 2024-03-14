<?php

namespace App\Providers;

use App\Bootstrap;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use SeQura\Core\Infrastructure\Http\CurlHttpClient;
use SeQura\Core\Infrastructure\Http\HttpClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HttpClient::CLASS_NAME, CurlHttpClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');

        Schema::defaultStringLength(191);

        Bootstrap::init();
    }
}
