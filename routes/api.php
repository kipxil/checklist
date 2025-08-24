<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\{
    MasterTwoONineController,
    BreakfastTwoONineController,
    LunchTwoONineController,
    DinnerTwoONineController,
    ThematicController
};

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->prefix('two_o_nine')->group(function () {
    // Master data Thematic
    Route::apiResource('thematics', ThematicController::class);

    // Masters
    Route::apiResource('masters', MasterTwoONineController::class);

    // Nested (Master -> Breakfast/Lunch/Dinner)
    Route::apiResource('masters.breakfasts', BreakfastTwoONineController::class);
    Route::apiResource('masters.lunches',    LunchTwoONineController::class);
    Route::apiResource('masters.dinners',    DinnerTwoONineController::class);
});

// Semua user yang login boleh membuat department & position sendiri
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('positions', PositionController::class);
    // Route::get('user', UserController::class);
    Route::apiResource('users', UserController::class);

    // User management khusus admin
    // Route::middleware('admin')->group(function () {
    //     Route::apiResource('users', UserController::class);
    // });
});