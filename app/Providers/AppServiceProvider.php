<?php

namespace App\Providers;

use App\Helpers\Variable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use App\Listeners\LogSentEmail;
use App\Models\VacancyModule\JobApplication;
use App\Observers\JobApplicationObserver;
use App\Services\TranslationService;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
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
        require_once app_path('Helpers/functions.php');

        $this->registerGates();

        // Define the 'api' rate limiter (required by throttle:api middleware)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?? client_ip() ?? 'unknown');
        });

        // Stricter limit for public form submissions (contact, vacancy apply, comments, live-session register)
        RateLimiter::for('forms', function (Request $request) {
            return Limit::perMinute(10)->by(client_ip() ?? $request->ip() ?? 'unknown');
        });

        // Register Translation Service
        $this->app->singleton(TranslationService::class);

        // Register Observers
        JobApplication::observe(JobApplicationObserver::class);

        // Register Email Logging Listeners
        Event::listen(MessageSent::class, LogSentEmail::class);
    }

    /**
     * Register authorization gates for role-based and permission-based access.
     * - accessAdmin / accessUser: used by middleware, return 403 when role is not attached.
     * - Any other ability: checked via Variable::hasPermission() (e.g. user_edit, blog_create).
     * Use with: Gate::allows('blog_edit'), @can('blog_edit'), $this->authorize('blog_edit'),
     * or route middleware: ->middleware('can:blog_edit').
     */
    private function registerGates(): void
    {
        Gate::define('accessAdmin', function ($user) {
            return $user->hasRole(Variable::ROLE_ADMIN);
        });

        Gate::define('accessUser', function ($user) {
            return $user->hasRole(Variable::ROLE_USER);
        });

        // Permission-based gates: any ability in Variable::$fullPermissions (or Spatie)
        // is checked here, so @can('user_edit'), middleware('can:blog_edit'), etc. work.
        Gate::before(function ($user, string $ability) {
            if (in_array($ability, ['accessAdmin', 'accessUser'], true)) {
                return null; // let the dedicated Gates above run
            }
            if ($user && Variable::hasPermission($ability)) {
                return true;
            }

            return null;
        });
    }
}
