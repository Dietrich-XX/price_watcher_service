<?php

use App\Http\Web\Actions\EmailVerifications\EmailVerifyAction;
use App\Http\Web\Actions\HealthCheckAction;
use Illuminate\Support\Facades\Route;

Route::get('/health-check/up', HealthCheckAction::class)->middleware('throttle:10,1');

// По логике правильней использовать Patch, но тег формы в шаблоне письма плохо поддерживается Email-клиентами
Route::get('/subscribers/email-verification/{token}', EmailVerifyAction::class)->name('subscribers.email-verification');
