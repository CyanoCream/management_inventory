<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncomingItemController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\MerchantPaymentController;
use App\Http\Controllers\Monitoring\CartItemController;
use App\Http\Controllers\Monitoring\SalesReportController;
use App\Http\Controllers\Monitoring\ShipmentController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\Provincecontroller;
use App\Http\Controllers\CityController;
use App\Http\Controllers\SubdistrictController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\IncomingItemController::class, 'index']);

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard & Incoming Items (bisa diakses Admin dan Operator)
    Route::get('/', [App\Http\Controllers\IncomingItemController::class, 'index'])->name('home');

    Route::resource('incoming-items', IncomingItemController::class);
    Route::post('incoming-items/{incomingItem}/toggle-verification', [IncomingItemController::class, 'toggleVerification'])
        ->name('incoming-items.toggle-verification');
    Route::get('incoming-items/{incomingItem}/print', [IncomingItemController::class, 'print'])
        ->name('incoming-items.print');
    Route::get('incoming-items/export', [IncomingItemController::class, 'export'])
        ->name('incoming-items.export');

    // Master Data (hanya bisa diakses Admin)
    Route::middleware(['check.role:Admin'])->group(function () {
        // Users
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-lock', [UserController::class, 'toggleLock'])
            ->name('users.toggle-lock');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Sub Categories
        Route::resource('sub-categories', SubCategoryController::class);
        Route::get('sub-categories/by-category/{category}', [SubCategoryController::class, 'getByCategory'])
            ->name('sub-categories.by-category');
    });
});




