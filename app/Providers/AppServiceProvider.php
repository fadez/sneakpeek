<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Disable mass assignment protection globally as we handle validation via Request classes
        Model::unguard();

        // Enforce strict behavior to catch lazy loading and missing attributes
        Model::shouldBeStrict();

        // Exclude fields from automatic trimming
        TrimStrings::except(['passphrase']);

        // Add ->createFresh() method to all model factories to avoid calling ->fresh() all the time
        Factory::macro('createFresh', function ($attributes = [], ?Model $parent = null) {
            /** @var (callable(array<string, mixed>): array<string, mixed>)|array<string, mixed> $attributes */
            return $this->create($attributes, $parent)->fresh();
        });

        // Throttle API requests to 60 per minute per IP address
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
