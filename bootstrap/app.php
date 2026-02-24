<?php

use App\Exceptions\EmailVerificationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('web')
                ->name("web.")
                ->group(base_path('routes/web.php'));

            // Можно вынести версионирование API в отдельный класс или метод ,но опять же этот код вряд ли где то переиспользуется,
            // пока не занимает кучу места можно хранить тут
            $apiPath = base_path('routes/api');
            foreach (glob($apiPath.'/*', GLOB_ONLYDIR) as $versionDir) {
                $version = basename($versionDir);
                $apiFile = $versionDir.'/api.php';

                if (file_exists($apiFile)) {
                    Route::middleware('api')
                        ->prefix("api/{$version}")
                        ->name("api.{$version}.")
                        ->group($apiFile);
                }
            }
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up', // Не работает при использование using
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (EmailVerificationException $exception) {
            return response()->view('email_verifications.verify_failed', [
                'errorMessage' => $exception->getMessage()
            ], $exception->getCode());
        });
    })
    ->create();
