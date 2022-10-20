<?php

namespace App\Http\Controllers;

use App\Models\{Order, Product};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'orders' => Order::get(),
            'products' => Product::get(),
        ]);
    }
}
