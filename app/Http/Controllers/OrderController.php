<?php

namespace App\Http\Controllers;

use App\Models\{Order, Product, Customer};
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function sales()
    {
        if (request()->ajax()) {
            $orders = Order::with('customer')->latest()->get();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" name="checkbox" id="check" class="checkbox" data-id="' . $row->id . '">';
                })
                ->editColumn('customer_id', function (Order $order) {
                    return $order->customer->name;
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
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }
        return view('orders.sales.index');
    }

    public function createSales()
    {
        return view('orders.sales.create', [
            'products' => Product::all(),
            'customers' => Customer::all(),
        ]);
    }

    public function storeSales()
    {
        if ($this->request->isAJAX()) {
            if ($this->validate(request(), [
                'name' => 'required',
                'category_id' => 'required',
            ]))
            {
                $name = $this->request->getPost('name');
                $category_id = $this->request->getPost('category_id');

                $total = count($name);
                for ($i = 0; $i < $total; $i++) {
                    $this->item->insert([
                        'name' => $name[$i],
                        'category_id' => $category_id[$i],
                    ]);
                }

                $message = [
                    'success' => $total . ' Data berhasil disimpan'
                ];

                return json_encode($message);
            }
        }
    }
}
