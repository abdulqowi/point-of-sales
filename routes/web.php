<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderPurchaseController;
use App\Http\Controllers\{HomeController, OrderSaleController, ProductController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('login', [LoginController::class, 'showLoginForm']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout',  [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('products', ProductController::class);
    Route::post('products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.deleteSelected');

    // Order Penjualan
    Route::get('orders/sales', [OrderSaleController::class, 'sales'])->name('sales.index');
    Route::get('orders/sales/create', [OrderSaleController::class, 'createSales'])->name('sales.create');
    Route::post('orders/sales', [OrderSaleController::class, 'storeSales'])->name('sales.store');
    Route::delete('orders/sales/{id}', [OrderSaleController::class, 'destroySales'])->name('sales.destroy');
    Route::get('orders/sales/{id}', [OrderPurchaseController::class, 'showSales'])->name('sales.show');

    // Order Pembelian
    Route::get('orders/purchases', [OrderPurchaseController::class, 'purchases'])->name('purchases.index');
    Route::get('orders/purchases/create', [OrderPurchaseController::class, 'createPurchases'])->name('purchases.create');
    Route::post('orders/purchases', [OrderPurchaseController::class, 'storepurchases'])->name('purchases.store');
    Route::delete('orders/purchases/{id}', [OrderPurchaseController::class, 'destroyPurchases'])->name('purchases.destroy');
    Route::get('orders/purchases/{id}', [OrderPurchaseController::class, 'showPurchases'])->name('purchases.show');

});
Route::resource('products', ProductController::class);
Route::post('products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.deleteSelected');
Route::resource('categories', CategoryController::class);
Route::post('categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.deleteSelected');
Route::resource('customers', CustomerController::class);
Route::post('customers/delete-selected', [CustomerController::class, 'deleteSelected'])->name('customers.deleteSelected');

Route::resource('suppliers', SupplierController::class);
Route::post('suppliers/delete-selected', [SupplierController::class, 'deleteSelected'])->name('suppliers.deleteSelected');
