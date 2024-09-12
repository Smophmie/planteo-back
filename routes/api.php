<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlantController;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);



Route::get('/isadmin', [UserController::class, 'isAdmin']);

Route::get('/connectedUser', [UserController::class, 'connectedUser']);

Route::get('/users', [UserController::class, 'index']);

Route::get('/users/{id}', [UserController::class, 'show']);

Route::put('/users/{id}', [UserController::class, 'update']);

Route::delete('/users/{id}', [UserController::class, 'destroy']);



Route::get('/plants', [PlantController::class, 'index']);

Route::get('/plants/{id}', [PlantController::class, 'show']);

Route::put('/plants/{id}', [PlantController::class, 'update']);

Route::delete('/plants/{id}', [PlantController::class, 'destroy']);



Route::get('/plantsbysowingperiod/{month}', [PlantController::class, 'getPlantsBySowingPeriod']);

Route::get('/plantsbyplantingperiod/{month}', [PlantController::class, 'getPlantsByPlantingPeriod']);

Route::get('/plantsbyharvestperiod/{month}', [PlantController::class, 'getPlantsByHarvestPeriod']);