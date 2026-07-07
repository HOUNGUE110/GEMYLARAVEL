<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarkerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Routes Publiques (Accessibles par tous) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/markers', [MarkerController::class, 'index']); 

// --- Routes Protégées (Nécessitent un Token Sanctum valide) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // CRUD des marqueurs sécurisé par l'authentification
    Route::post('/markers', [MarkerController::class, 'store']);
    Route::put('/markers/{id}', [MarkerController::class, 'update']);
    Route::delete('/markers/{id}', [MarkerController::class, 'destroy']);
});