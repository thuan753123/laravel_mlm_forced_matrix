<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CommissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    // Matrix routes
    Route::prefix('matrix')->group(function () {
        Route::get('/me', [MatrixController::class, 'me']);
        Route::get('/tree/{user?}', [MatrixController::class, 'tree']);
        Route::get('/stats', [MatrixController::class, 'stats']);
        Route::get('/downline', [MatrixController::class, 'downline']);
        Route::get('/visualization', [MatrixController::class, 'visualization']);
    });
    
    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/stats', [OrderController::class, 'stats']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/pay', [OrderController::class, 'pay']);
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
    });
    
    // Commission routes
    Route::prefix('commissions')->group(function () {
        Route::get('/me', [CommissionController::class, 'me']);
        Route::get('/summary', [CommissionController::class, 'summary']);
        Route::get('/history', [CommissionController::class, 'history']);
        Route::get('/stats', [CommissionController::class, 'stats']);
        Route::get('/{commission}', [CommissionController::class, 'show']);
    });
    
    // Cycle routes
    Route::prefix('cycles')->group(function () {
        Route::get('/current', [CommissionController::class, 'currentCycle']);
        Route::get('/history', [CommissionController::class, 'cycleHistory']);
        Route::post('/close', [CommissionController::class, 'closeCycle']);
    });
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::prefix('config')->group(function () {
            Route::get('/', [ConfigController::class, 'index']);
            Route::post('/', [ConfigController::class, 'update']);
            Route::post('/reset', [ConfigController::class, 'reset']);
            Route::get('/history', [ConfigController::class, 'history']);
            Route::post('/validate', [ConfigController::class, 'validate']);
        });
    });
});

// Rate limiting for sensitive endpoints
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay']);
});