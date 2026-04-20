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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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
        $this->configureModels();
        $this->configureRequests();
        $this->configureRateLimits();
        $this->configureFeatures();
        $this->configureCommands();
        $this->configureVite();
    }

    /**
     * Configure the application's models and model factories.
     */
    private function configureModels(): void
    {
        // Disable mass assignment protection since validation is handled via form requests
        Model::unguard();

        // Enforce strict behavior to prevent lazy loading and accessing missing attributes
        Model::shouldBeStrict();

        // Add ->createFresh() method to all model factories to avoid calling ->fresh() on every created instance
        Factory::macro('createFresh', function ($attributes = [], ?Model $parent = null) {
            /** @var (callable(array<string, mixed>): array<string, mixed>)|array<string, mixed> $attributes */
            return $this->create($attributes, $parent)->fresh();
        });
    }

    /**
     * Configure the application's request handling.
     */
    private function configureRequests(): void
    {
        // Force HTTPS scheme on all generated URLs in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Reject requests containing fields not present in form request validation rules
        FormRequest::failOnUnknownFields();

        // Exclude specific fields from automatic trimming
        TrimStrings::except(['passphrase']);

        // Custom privacy-first session handler that doesn't store any user information
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
    }

    /**
     * Configure the application's rate limiting.
     */
    private function configureRateLimits(): void
    {
        // Limit requests for API routes based on the client's IP address
        RateLimiter::for('api', function (Request $request) {
            $limit = $this->app->environment('production') ? 60 : 600;

            return Limit::perMinute($limit)->by($request->ip());
        });
    }

    /**
     * Configure the Laravel Pennant features.
     */
    private function configureFeatures(): void
    {
        // Use the session ID as the feature flag scope, as the application has no authenticated users
        Feature::resolveScopeUsing(fn () => Session::getId());

        // 1-in-100 chance to show a lucky message to the user
        Feature::define('lucky-message', Lottery::odds(1, 100));

        // Randomly assign users to a control or variant group for A/B testing
        Feature::define('ab-group', fn () => Arr::random(['control', 'variant']));
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->environment('production'));
    }

    /**
     * Configure Vite asset prefetching.
     */
    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }
}
