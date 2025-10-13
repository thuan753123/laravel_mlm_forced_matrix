<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CommissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect('/dashboard');
});

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Matrix routes
    Route::prefix('matrix')->group(function () {
        Route::get('/', [MatrixController::class, 'index'])->name('matrix.index');
        Route::get('/tree', [MatrixController::class, 'tree'])->name('matrix.tree');
        Route::get('/stats', [MatrixController::class, 'stats'])->name('matrix.stats');
    });
    
    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });
    
    // Commission routes
    Route::prefix('commissions')->group(function () {
        Route::get('/', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/summary', [CommissionController::class, 'summary'])->name('commissions.summary');
        Route::get('/history', [CommissionController::class, 'history'])->name('commissions.history');
    });
    
    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::prefix('config')->group(function () {
            Route::get('/', [ConfigController::class, 'index'])->name('admin.config.index');
            Route::post('/', [ConfigController::class, 'update'])->name('admin.config.update');
            Route::post('/reset', [ConfigController::class, 'reset'])->name('admin.config.reset');
        });
    });
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});