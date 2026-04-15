<?php

namespace App\Providers;

use App\Extensions\Session\DatabaseSessionHandler;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Lottery;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use UnitEnum;

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
        // Disable mass assignment protection since validation is handled via form requests
        Model::unguard();

        // Enforce strict behavior to prevent lazy loading and accessing missing attributes
        Model::shouldBeStrict();

        // Reject any request fields not specified in form request validation rules
        FormRequest::failOnUnknownFields();

        // Exclude specific fields from automatic trimming
        TrimStrings::except(['passphrase']);

        Session::extend('database', function (Application $app): DatabaseSessionHandler {
            /** @var UnitEnum|string|null $connection */
            $connection = Config::get('session.connection');

            return new DatabaseSessionHandler(
                connection: $app->make(ConnectionResolverInterface::class)->connection($connection),
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

        // Use session IDs as the feature flag scope since user authentication is not implemented
        Feature::resolveScopeUsing(fn () => Session::getId());

        Feature::define('lucky-message', Lottery::odds(1, 100));

        Feature::define('ab-group', fn () => Arr::random(['control', 'variant']));

        // Add ->createFresh() method to all model factories to avoid calling ->fresh() all the time
        Factory::macro('createFresh', function ($attributes = [], ?Model $parent = null) {
            /** @var (callable(array<string, mixed>): array<string, mixed>)|array<string, mixed> $attributes */
            return $this->create($attributes, $parent)->fresh();
        });
    }
}
