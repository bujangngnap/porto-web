<?php

use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\PortfolioApiController;
use App\Http\Controllers\Api\VisitApiController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json([
    'ok' => true,
    'service' => 'ashr-portfolio-laravel',
    'time' => now()->toIso8601String(),
]));

Route::get('/portfolio', [PortfolioApiController::class, 'index']);
Route::get('/comments', [CommentApiController::class, 'index']);
Route::post('/comments', [CommentApiController::class, 'store'])->middleware('throttle:15,1');
Route::post('/contact', [ContactApiController::class, 'store'])->middleware('throttle:10,1');
Route::post('/visit', [VisitApiController::class, 'store'])->middleware('throttle:60,1');
