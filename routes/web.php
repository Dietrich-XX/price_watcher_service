<?php

use App\Http\Web\HealthCheckAction;
use Illuminate\Support\Facades\Route;

Route::get('/health-check/up', HealthCheckAction::class)->middleware('throttle:10,1');
