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

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Matrix routes
    Route::prefix('matrix')->group(function () {
        Route::get('/', [MatrixController::class, 'index'])->name('matrix.index');
        Route::get('/tree', [MatrixController::class, 'tree'])->name('matrix.tree');
        Route::get('/stats', [MatrixController::class, 'stats'])->name('matrix.stats');
        Route::get('/me', [MatrixController::class, 'me'])->name('matrix.me');
        Route::get('/downline', [MatrixController::class, 'downline'])->name('matrix.downline');
        Route::get('/visualization', [MatrixController::class, 'visualization'])->name('matrix.visualization');
    });
    
    // Order routes
    Route::prefix('orders')->group(function () {
        // Render UI
        Route::get('/', [OrderController::class, 'page'])->name('orders.index');
        // API endpoints under web session for simplicity
        Route::get('/list', [OrderController::class, 'index'])->name('orders.list');
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
        // MLM Config
        Route::prefix('config')->group(function () {
            Route::get('/', [ConfigController::class, 'index'])->name('admin.config.index');
            Route::post('/', [ConfigController::class, 'update'])->name('admin.config.update');
            Route::post('/reset', [ConfigController::class, 'reset'])->name('admin.config.reset');
        });

        // Policies Management
        Route::prefix('policies')->name('admin.policies.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PolicyController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\PolicyController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\PolicyController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\PolicyController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\PolicyController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\PolicyController::class, 'destroy'])->name('destroy');
        });

        // Commission Policies Management
        Route::prefix('commissions')->name('admin.commissions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CommissionPolicyController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\CommissionPolicyController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\CommissionPolicyController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\CommissionPolicyController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\CommissionPolicyController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\CommissionPolicyController::class, 'destroy'])->name('destroy');
        });
    });
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
