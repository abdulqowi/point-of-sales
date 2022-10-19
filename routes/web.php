<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout',  [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('products', ProductController::class);
Route::post('products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.deleteSelected');
Route::resource('categories', CategoryController::class);
Route::post('categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.deleteSelected');
Route::resource('customers', CustomerController::class);
Route::post('customers/delete-selected', [CustomerController::class, 'deleteSelected'])->name('customers.deleteSelected');