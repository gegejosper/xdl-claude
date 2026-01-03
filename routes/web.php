<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
Route::middleware(['web'])->group(function () {
// Route::get('/', function () {
//     return view('layouts.panel');
// });
Route::get('/', function () {
    return view('auth.login');
})->name('home');
Route::middleware(['auth'])->get('/panel/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('panel')->middleware(['auth', 'device.verify'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class);
        
    });
    Route::middleware('can:manage-users-related')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });

    Route::middleware('can:manage-settings')->group(function () {
        Route::middleware(['web', 'auth'])->group(function () {
    Route::get('logs', [LogViewerController::class, 'index'])
        ->name('logs.index');
    });
    });

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('products', ProductController::class);

    


});

Route::get('/inactive', function () {
    return view('auth.inactive');
})->name('inactive');
require __DIR__.'/auth.php';
});
