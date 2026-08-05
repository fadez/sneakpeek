<?php

declare(strict_types=1);

namespace App\Providers;

use App\Extensions\Session\DatabaseSessionHandler;
use Carbon\CarbonImmutable;
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
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Lottery;
use Illuminate\Support\ServiceProvider;
use Laravel\Head\Enums\ImageType;
use Laravel\Head\Enums\OgType;
use Laravel\Head\Enums\TwitterCard;
use Laravel\Head\ErrorPages;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;
use Laravel\Pennant\Feature;
use UnitEnum;

final class AppServiceProvider extends ServiceProvider
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
        $this->configureDefaults();
        $this->configureModels();
        $this->configureSession();
        $this->configureRequests();
        $this->configureRateLimits();
        $this->configureFeatures();
        $this->configureVite();
        $this->configureHead();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    private function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(app()->isProduction());
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

        /**
         * Add "createFresh" method to all model factories to avoid calling "fresh" on every created instance.
         *
         * @param  (callable(array<string, mixed>): array<string, mixed>)|array<string, mixed>  $attributes
         * @param  Model|null  $parent
         */
        Factory::macro('createFresh', function (callable|array $attributes = [], ?Model $parent = null) {
            /** @var array<string, mixed> $attributes */
            return $this->create($attributes, $parent)->fresh();
        });
    }

    /**
     * Configure the application's session handling.
     */
    private function configureSession(): void
    {
        // Use custom privacy-first session handler that doesn't store any user information
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
     * Configure the application's request handling.
     */
    private function configureRequests(): void
    {
        // Force HTTPS scheme on all generated URLs in production
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        // Reject requests containing fields not present in form request validation rules
        FormRequest::failOnUnknownFields();

        // Exclude specific fields from automatic trimming
        TrimStrings::except(['passphrase']);
    }

    /**
     * Configure the application's rate limiting.
     */
    private function configureRateLimits(): void
    {
        // Set global rate limit for all API requests per client IP
        RateLimiter::for('api', function (Request $request) {
            $limit = app()->isProduction() ? 60 : 600;

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

        // Show a lucky message to the user with a 1-in-100 chance
        Feature::define('lucky-message', Lottery::odds(1, 100));

        // Assign users randomly to either the control or variant group for A/B testing
        Feature::define('ab-group', fn () => Arr::random(['control', 'variant']));
    }

    /**
     * Configure Vite asset prefetching.
     */
    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    /**
     * Configure default document head metadata.
     */
    private function configureHead(): void
    {
        Head::defaults(fn (HeadBuilder $head): HeadBuilder => $head
            ->title(Config::string('app.name'), suffix: ' | ' . Config::string('app.name'))
            ->description('Secure, one-time secret sharing made simple.')
            ->canonical()
            ->viewport('width=device-width, initial-scale=1')
            ->searchableByRobots()
            ->og(type: OgType::Website, image: asset('og.png'), siteName: Config::string('app.name'))
            ->twitter(card: TwitterCard::SummaryWithLargeImage)
            ->favicon('/favicon/favicon.ico')
            ->icon('/favicon/favicon.svg', type: ImageType::Svg)
            ->icon('/favicon/favicon-96x96.png', type: ImageType::Png, sizes: '96x96')
            ->appleTouchIcon('/favicon/apple-touch-icon.png', sizes: '180x180')
            ->manifest('/favicon/site.webmanifest')
            ->appleWebAppTitle(Config::string('app.name'))
        );

        Head::errors(function (ErrorPages $errors) {
            $errors->defaults(robots: 'noindex, follow');

            $errors->status(418, fn (HeadBuilder $head): HeadBuilder => $head
                ->title("I'm a teapot", exact: true)
                ->description("Who would've known?"));
        });
    }
}
