<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(){
        $data = DB::table('categories')->get();
        return view('category.index', compact('data'));
    }

    public function create(){
        return view('category.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' =>'required|unique:categories', 
        ]);

        Category::create([
            'name' => $request->name
        ]);
        return redirect()->route('category.index');
    }
    public function destroy() {
        DB::table('categories')->where('id', $this->id)->delete();
        return redirect()->route('category.index');
    }





























}