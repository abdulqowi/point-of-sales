<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\LoginController;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round;
use App\Http\Controllers\{OrderController, ProductController};

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
    Route::get('/', function () {
        return view('welcome');
    });
    Route::resource('products', ProductController::class);
    Route::post('products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.deleteSelected');

    Route::get('orders/sales', [OrderController::class, 'sales'])->name('sales.index');
    Route::get('orders/sales/create', [OrderController::class, 'createSales'])->name('sales.create');
    Route::post('orders/sales', [OrderController::class, 'storeSales'])->name('sales.store');
    Route::delete('orders/sales/{id}', [OrderController::class, 'destroySales'])->name('sales.destroy');

});
Route::resource('products', ProductController::class);
Route::post('products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.deleteSelected');
Route::resource('categories', CategoryController::class);
Route::post('categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.deleteSelected');
Route::resource('customers', CustomerController::class);
Route::post('customers/delete-selected', [CustomerController::class, 'deleteSelected'])->name('customers.deleteSelected');
Route::resource('suppliers', SupplierController::class);
Route::post('suppliers', [SupplierController::class, 'deleteSelected'])->name('suppliers.deleteSelected');