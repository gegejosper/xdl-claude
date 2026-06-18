<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ItemPriceController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\BindingDeviceController;
use Illuminate\Support\Facades\Auth;

Route::middleware(['web'])->group(function () {
// Route::get('/', function () {
//     return view('layouts.panel');
// });
Route::get('/', function () {
    return view('auth.login');
})->name('home');
// Generic /panel/dashboard — redirects to the correct role dashboard
Route::middleware(['auth', 'checkActive'])->get('/panel/dashboard', function () {
    $user = Auth::user();
    if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('staff') || $user->hasRole('cashier')) {
        return redirect()->route('staff.dashboard');
    }
    return redirect('/');
})->name('dashboard');
Route::get('/search-town', [AddressController::class, 'search_town'])->name('search_town');
Route::get('/search-barangay', [AddressController::class, 'search_barangay'])->name('search_barangay');

Route::prefix('panel')->middleware(['auth', 'checkActive'])->group(function () {

    Route::middleware('device.verify')->group(function() {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
        
        Route::middleware('can:manage-users')->group(function () {
            Route::resource('users', UserController::class);
            Route::delete('/users/unbind/{unbind}', [UserController::class, 'unbinding'])->name('users.unbind');
            Route::patch('/users/restricted/{restrict}', [UserController::class, 'restricted'])->name('users.restricted');
            
        });
        Route::middleware('can:manage-users-related')->group(function () {
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
        });

        Route::middleware('can:manage-settings')->group(function () {
            Route::get('logs', [LogViewerController::class, 'index'])->name('logs.index');
        });

        Route::prefix('customers')->name('customers.')->group(function() {
            Route::get('/', [CustomerController::class,'show_customers'])->middleware('can:view_customers')->name('show_customers');
            Route::get('/{customer_id}', [CustomerController::class, 'view_customer'])->middleware('can:view_customers')->name('view_customer');
            Route::post('/add', [CustomerController::class,'add_customer'])->middleware('can:create_customers')->name('add_customer');
            Route::post('/edit', [CustomerController::class,'edit_customer'])->middleware('can:edit_customers')->name('edit_customer');
            Route::post('/search', [CustomerController::class,'search_customers'])->middleware('can:view_customers')->name('search_customers');
            Route::post('/modify', [CustomerController::class,'modify_customer'])->middleware('can:edit_customers')->name('modify_customer');
            Route::post('/filter/branch', [CustomerController::class,'filter_by_branch'])->middleware('can:view_customers')->name('filter_by_branch');
        });

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.dashboard');

    // Staff dashboard — primary URL for staff role
    Route::get('/staff/dashboard', [CashierController::class, 'index'])->name('staff.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('products', ProductController::class);

    // ─── Transactions (Job Orders) ─────────────────────────────────────────
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/',           [TransactionController::class, 'index'])->name('index');
        Route::get('/create',     [TransactionController::class, 'create'])->name('create');
        Route::post('/',          [TransactionController::class, 'store'])->name('store');
        Route::get('/{id}',       [TransactionController::class, 'show'])->name('show');
        Route::get('/{id}/edit',  [TransactionController::class, 'edit'])->name('edit');
        Route::post('/{id}',      [TransactionController::class, 'update'])->name('update');
        Route::post('/{id}/payment',           [TransactionController::class, 'receive_payment'])->name('payment');
        Route::get('/payments/{payment_id}/receipt', [TransactionController::class, 'payment_receipt'])->name('payment.receipt');
        Route::post('/{id}/approve',  [TransactionController::class, 'approve'])->name('approve');
        Route::post('/{id}/finalize', [TransactionController::class, 'finalize'])->name('finalize');
        Route::post('/{id}/claim',    [TransactionController::class, 'update_claim'])->name('claim');
    });

    // ─── Expenses & Purchases ──────────────────────────────────────────────
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/purchases',  [ExpenseController::class, 'purchases'])->name('purchases');
        Route::get('/',           [ExpenseController::class, 'expenses'])->name('index');
        Route::post('/',          [ExpenseController::class, 'store'])->name('store');
        Route::post('/{id}',      [ExpenseController::class, 'update'])->name('update');
        Route::delete('/{id}',    [ExpenseController::class, 'destroy'])->name('destroy');
    });

    //binding devices
    Route::group(['prefix' => 'binding_devices'], function () {
    Route::get('/', [BindingDeviceController::class, 'index'])->name('binding_devices.index');
    Route::delete('/{bindingDevice}', [BindingDeviceController::class, 'destroy'])->name('binding_devices.destroy');
    });

    // ─── Item Prices (settings) ────────────────────────────────────────────
    Route::get('/item-prices/{type}', [ItemPriceController::class, 'get_price'])->name('item_prices.get');
    Route::middleware('can:manage-settings')->prefix('item-prices')->name('item_prices.')->group(function () {
        Route::get('/',  [ItemPriceController::class, 'index'])->name('index');
        Route::post('/', [ItemPriceController::class, 'store'])->name('store');
    });

    // ─── Branches ──────────────────────────────────────────────────────────
    Route::middleware('can:manage-settings')->prefix('branches')->name('branches.')->group(function () {
        Route::get('/',                  [BranchController::class, 'index'])->name('index');
        Route::post('/',                 [BranchController::class, 'store'])->name('store');
        Route::get('/{id}',              [BranchController::class, 'show'])->name('show');
        Route::put('/{id}',              [BranchController::class, 'update'])->name('update');
        Route::delete('/{id}',          [BranchController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/users',       [BranchController::class, 'add_user'])->name('add_user');
        Route::post('/{id}/users/remove',[BranchController::class, 'remove_user'])->name('remove_user');
    });

    
});

});

Route::get('/inactive', function () {
    return view('auth.inactive');
})->name('inactive');
require __DIR__.'/auth.php';
});
