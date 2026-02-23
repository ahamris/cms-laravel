<?php

use App\Http\Middleware\CheckIfAdmin;
use App\Http\Middleware\CheckIfUser;
use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware for web routes
        $middleware->web(append: [
            SetLocaleMiddleware::class,
        ]);

        $middleware->alias([
            'admin' => CheckIfAdmin::class,
            'user' => CheckIfUser::class,
            'locale' => SetLocaleMiddleware::class,
        ]);
    })->withSchedule(function (Schedule $schedule) {
        // Prune old datas from models daily at 01:15
        $schedule->command('model:prune')
            ->daily()
            ->at('07:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/model-prunes.log'));

        // Import translations daily at 02:00
        $schedule->command('translations:import')
            ->daily()
            ->at('02:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/translation-imports.log'));

        // Clear specific log files weekly
        $schedule->command('logs:clear')
            ->weeklyOn(0, '3:00') // Runs every Sunday at 3:00 AM
            ->when(fn () => now()->weekOfYear % 2 === 0); // on even weeks

        // Process scheduled content every hour
        $schedule->command('content:process-scheduled')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduled-content.log'));

        // Update content performance daily
        $schedule->command('content:update-performance')
            ->daily()
            ->at('02:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/content-performance.log'));

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
