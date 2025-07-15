<?php

use App\Http\Controllers\HelloController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\LogMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/hello', [HelloController::class, 'index'])
    ->middleware(LogMiddleware::class)
    ->name('hello.index');

Route::apiResource('tasks', TaskController::class);


