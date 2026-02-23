<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Можно вынести версионирование API в хелпер ,но опять же этот код вряд ли где то переиспользуется,
            // пока не занимает кучу места можно хранить тут
            $apiPath = base_path('routes/api');
            foreach (glob($apiPath.'/*', GLOB_ONLYDIR) as $versionDir) {
                $version = basename($versionDir);
                $apiFile = $versionDir.'/api.php';

                if (file_exists($apiFile)) {
                    Route::middleware('api')
                        ->prefix("api/{$version}")
                        ->group($apiFile);
                }
            }
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
