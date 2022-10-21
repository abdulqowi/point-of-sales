<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\{CategoryController, CustomerController, SupplierController, OrderPurchaseController, HomeController, OrderSaleController, ProductController};


Route::get('login', [LoginController::class, 'showLoginForm']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout',  [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    // Produk Modul
    Route::resource('products', ProductController::class);
    Route::post('products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.deleteSelected');

    // Order Penjualan
    Route::get('orders/sales', [OrderSaleController::class, 'sales'])->name('sales.index');
    Route::get('orders/sales/create', [OrderSaleController::class, 'createSales'])->name('sales.create');
    Route::post('orders/sales', [OrderSaleController::class, 'storeSales'])->name('sales.store');
    Route::delete('orders/sales/{id}', [OrderSaleController::class, 'destroySales'])->name('sales.destroy');
    Route::get('orders/sales/{id}', [OrderSaleController::class, 'showSales'])->name('sales.show');
    Route::get('orders/sales/{id}/print', [OrderSaleController::class, 'printSales'])->name('sales.print');
    Route::post('orders/sales/{id}/status', [OrderSaleController::class, 'updateStatus'])->name('sales.status');

    // Order Pembelian
    Route::get('orders/purchases', [OrderPurchaseController::class, 'purchases'])->name('purchases.index');
    Route::get('orders/purchases/create', [OrderPurchaseController::class, 'createPurchases'])->name('purchases.create');
    Route::post('orders/purchases', [OrderPurchaseController::class, 'storepurchases'])->name('purchases.store');
    Route::delete('orders/purchases/{id}', [OrderPurchaseController::class, 'destroyPurchases'])->name('purchases.destroy');
    Route::get('orders/purchases/{id}', [OrderPurchaseController::class, 'showPurchases'])->name('purchases.show');
    Route::get('orders/purchases/{id}/print', [OrderPurchaseController::class, 'printPurchases'])->name('purchases.print');

    /** Categories */
    Route::resource('categories', CategoryController::class);
    Route::post('categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.deleteSelected');
    /** Customer */
    Route::resource('customers', CustomerController::class);
    Route::post('customers/delete-selected', [CustomerController::class, 'deleteSelected'])->name('customers.deleteSelected');
    /** Supplier */
    Route::resource('suppliers', SupplierController::class);
    Route::post('suppliers/delete-selected', [SupplierController::class, 'deleteSelected'])->name('suppliers.deleteSelected');
});
