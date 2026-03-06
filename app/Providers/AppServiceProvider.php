<?php

namespace App\Providers;

use App\Extensions\Session\DatabaseSessionHandler;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
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

        Session::extend('database', function (Application $app) {
            /** @var \UnitEnum|string|null $connection */
            $connection = Config::get('session.connection');

            return new DatabaseSessionHandler(
                connection: $app->make('db')->connection($connection),
                table: Config::string('session.table'),
                minutes: Config::integer('session.lifetime'),
                container: $app
            );
        });

        // Configure rate limiting for API routes based on the client's IP address
        RateLimiter::for('api', function (Request $request) {
            $limit = app()->environment('production') ? 60 : 600;

            return Limit::perMinute($limit)->by($request->ip());
        });

        // Add ->createFresh() method to all model factories to avoid calling ->fresh() all the time
        Factory::macro('createFresh', function ($attributes = [], ?Model $parent = null) {
            /** @var (callable(array<string, mixed>): array<string, mixed>)|array<string, mixed> $attributes */
            return $this->create($attributes, $parent)->fresh();
        });
    }
}
