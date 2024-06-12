<?php

namespace App\Providers;

use App\Contracts\UrlShortenerInterface;
use App\Contracts\UrlValidatorInterface;
use App\Services\GoogleSafeBrowsingUrlValidator;
use App\Services\UrlShortener;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UrlValidatorInterface::class, GoogleSafeBrowsingUrlValidator::class);
        $this->app->bind(UrlShortenerInterface::class, UrlShortener::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
