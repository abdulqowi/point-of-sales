<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index(){
        if (request()->ajax()){
            $Supplier = Supplier::latest()->get();
            return DataTables::of($Supplier)
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
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm" id="editSupplier">Edit</a>
                                <form action=" ' . route('suppliers.destroy', $row->id) . '" method="POST">
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
        return view('suppliers.index', [
            'title' => 'Pelanggan',
        ]);
    }

    public function create(){
        return view('suppliers.create');
    }
    public function store(){
        $this->validate(request(), [
            'name' =>'required|string|max:255',
            'address' =>'required|string|max:255'
        ]);
        Supplier::updateOrCreate(
            ['id' => request('supplier_id')]    ,
            ['name' => request('name'),
            'address' => request('address')],
        );
    }
    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return back();
    }
    
    public function edit(Supplier $supplier){
        return response()->json($supplier);
    }
    public function deleteSelected()
    {
        $id = request('id');
        Supplier::whereIn('id', $id)->delete();
        return response()->json(['code' => 1, 'msg' => 'Berhasil dihapus']);
    }
}
