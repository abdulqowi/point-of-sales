<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $Category=Category::latest()->get();
            return DataTables::of($Category)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" name="checkbox" id="check" class="checkbox" data-id="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="btn-group">
                            <a class="badge bg-navy dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm" id="editCategory">Edit</a>
                                <form action=" ' . route('categories.destroy', $row->id) . '" method="POST">
                                    <button type="submit" class="dropdown-item" onclick="return confirm(\'Apakah yakin ingin menghapus ini?\')">Hapus</button>
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                </form>
                            </div>
                        </div>';
                    return $btn;
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }
        return view('categories.index', [
            'title' => 'Kategori',
        ]);
    }

    public function create(){
        return view('categories.create');
    }

    public function store(){
        $this->validate(request(), [
            'name' =>'required|string|max:255',
        ]);
        Category::updateOrCreate(
            ['id' => request('category_id')],
            ['name' => request('name')],
        );
        

        

    }
    public function destroy(Category $category) {
        $category->delete();
        return back();
    }
    
    public function edit(Category $category){
        return response()->json($category);
    }
    public function deleteSelected()
    {
        $id = request('id');
        Category::whereIn('id', $id)->delete();
        return response()->json(['code' => 1, 'msg' => 'Berhasil dihapus']);
    }



























}