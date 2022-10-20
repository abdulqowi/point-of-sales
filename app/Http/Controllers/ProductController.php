<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\{Category, Order, Product};
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ProductRequest;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $products = Product::with('category')->latest()->get();
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" name="checkbox" id="check" class="checkbox" data-id="' . $row->id . '">';
                })
                ->editColumn('category_id', function (Product $product) {
                    return $product->category->name;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="btn-group">
                            <a class="badge bg-navy dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" id="showProduct" class="btn btn-sm btn-primary">View</a>
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm" id="editProduct">Edit</a>
                                <form action=" ' . route('products.destroy', $row->id) . '" method="POST">
                                    <button type="submit" class="dropdown-item" onclick="return confirm(\'Apakah yakin ingin menghapus ini?\')">Hapus</button>
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                </form>
                            </div>
                        </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'checkbox', 'image', 'action'])
                ->make(true);
        }
        return view('products.index', [
            'title' => 'Data Product',
            'categories' => Category::get(),
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }
    public function store(ProductRequest $request)
    {
        $request->validated();

        Product::updateOrCreate(
            ['id' => request('product_id')],
            [
                'name' => request('name'),
                'price' => request('price'),
                'quantity' => request('quantity'),
                'category_id' => request('category_id'),
            ]
        );
    }

    public function edit(Product $product)
    {
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back();
    }

    public function deleteSelected()
    {
        $id = request('id');
        Product::whereIn('id', $id)->delete();
        return response()->json(['code' => 1, 'msg' => 'Data product berhasil dihapus']);
    }

    // public function import()
    // {
    //     request()->validate([
    //         'file' => 'required|mimes:csv,xls,xlsx'
    //     ]);

    //     Excel::import(new MembersImport, request()->file('file'));

    //     toast('Data anggota berhasil diimport!', 'success');
    //     return redirect()->route('members.index');
    // }

    // public function export()
    // {
    //     return Excel::download(new MembersExport, time() . 'members.xlsx');
    // }

    // public function printPDF()
    // {
    //     $members = Member::all();
    //     // $pdf = app('dompdf.wrapper');
    //     $pdf = PDF::loadView('members.pdf', compact('members'))->setPaper('a4', 'landscape');
    //     return $pdf->stream();
    // }
}
