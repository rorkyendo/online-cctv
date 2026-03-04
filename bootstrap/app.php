<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\RefreshEzvizTokens;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Percaya semua proxy (aPanel reverse proxy → Docker nginx → PHP-FPM)
        // Tanpa ini, Laravel tidak tahu request aslinya HTTPS → asset URL jadi http://
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'auth'        => \App\Http\Middleware\CheckAuthenticated::class,
            'checkAccess' => \App\Http\Middleware\CheckAccess::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Refresh EZVIZ access token setiap hari jam 02:00
        // Token EZVIZ valid 7 hari; ini memperbarui token yang < 24 jam lagi kadaluwarsa
        $schedule->command('ezviz:refresh-tokens --hours=24')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/ezviz-token-refresh.log'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
