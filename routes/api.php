<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\FavoriteController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/isadmin', [UserController::class, 'isAdmin']);

    Route::get('/connectedUser', [UserController::class, 'connectedUser']);

    Route::get('/users', [UserController::class, 'index']);

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::put('/users/{id}', [UserController::class, 'update']);

    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/favorites', [UserController::class, 'getFavorites']);

    Route::get('/favorite/{id}', [FavoriteController::class, 'isFavorite']);

    Route::post('/favorites', [FavoriteController::class, 'store']);

    Route::delete('/favorites', [FavoriteController::class, 'destroy']);
});


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::get('/plants', [PlantController::class, 'index']);

Route::get('/plantsbyname', [PlantController::class, 'getPlantsByName']);

Route::get('/plants/{id}', [PlantController::class, 'show']);

Route::put('/plants/{id}', [PlantController::class, 'update']);

Route::delete('/plants/{id}', [PlantController::class, 'destroy']);


Route::get('/plantsbyperiod/{month}/{periodType}', [PlantController::class, 'getPlantsByPeriod']);