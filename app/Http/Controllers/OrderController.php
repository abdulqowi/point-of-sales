<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\{Order, Product, Customer};

class OrderController extends Controller
{
    public function sales()
    {
        if (request()->ajax()) {
            $orders = Order::whereNotNull('customer_id')->latest()->get();
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
        $record = Order::latest()->first();
        if (isset($record)) {
            $expNum = explode('-', $record->order_number);
            $nextInvoiceNumber = $expNum[0] . '-' . $expNum[1] . '-' . ($expNum[2] + '1');
        } else {
            $nextInvoiceNumber = 'INV-' . date('dmy') . '-10001';
        }

        // $this->validate(request(), [
        //     'name' => 'required',
        //     'category_id' => 'required',
        // ]);

        $order = Order::create([
            'date' => request('date'),
            'order_number' => $nextInvoiceNumber,
            'status' => request('status') ?? 'pending',
            'customer_id' => request('customer_id'),
        ]);
        for ($i = 0; $i < count(request('product')); $i++) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => request('product')[$i],
                'quantity' => request('quantity')[$i],
                'price' => request('price')[$i],
            ]);
        }


        return redirect()->route('sales.index');
    }


    public function purchases()
    {
        if (request()->ajax()) {
            $orders = Order::whereNotNull('supplier_id')->with('supplier')->latest()->get();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->editColumn('supplier_id', function (Order $order) {
                    return $order->supplier->name;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="btn-group">
                            <a class="badge bg-navy dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" id="showProduct" class="btn btn-sm btn-primary">View</a>
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm" id="editProduct">Print</a>
                                <form action=" ' . route('purchases.destroy', $row->id) . '" method="POST">
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
        return view('orders.purchases.index');
    }

    public function createPurchases()
    {
        $record = Order::latest()->first();
        if (isset($record)) {
            $expNum = explode('-', $record->order_number);
            $nextInvoiceNumber = $expNum[0] . '-' . $expNum[1] . '-' . ($expNum[2] + '1');
        } else {
            $nextInvoiceNumber = 'INV-' . date('dmy') . '-10001';
        }
        return view('orders.purchases.create', [
            'products' => Product::all(),
            'suppliers' => Supplier::all(),
            'order_number' => $nextInvoiceNumber,
        ]);
    }

    public function storePurchases()
    {
        if (request()->ajax()) {
            $record = Order::latest()->first();
            if (isset($record)) {
                $expNum = explode('-', $record->order_number);
                $nextInvoiceNumber = $expNum[0] . '-' . $expNum[1] . '-' . ($expNum[2] + '1');
            } else {
                $nextInvoiceNumber = 'INV-' . date('dmy') . '-10001';
            }
            DB::transaction(function () use ($nextInvoiceNumber) {
                $order = Order::create([
                    'date' => request('date'),
                    'order_number' => $nextInvoiceNumber,
                    'status' => request('status') ?? 'pending',
                    'supplier_id' => request('supplier_id'),
                ]);
                for ($i = 0; $i < count(request('product')); $i++) {
                    $orderDetail = OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => request('product')[$i],
                        'quantity' => request('quantity')[$i],
                        'price' => request('price')[$i],
                    ]);

                    Product::where('id', $orderDetail->product_id)->update([
                        'quantity' => DB::raw("quantity + $orderDetail->quantity")
                    ]);
                }
            });

            $message = [
                'success' => 'Data berhasil disimpan',
            ];

            return response()->json($message);
        }
    }

    public function destroyPurchases($id)
    {
        $order = Order::with('products')->find($id);
        $orderDetail = DB::table('order_details')->where('order_id', $order->id)->get();
        foreach ($orderDetail as $value) {
            Product::where('id', $value->product_id)->update([
                'quantity' => DB::raw("quantity-$value->quantity")
            ]);
        }
        $order->delete();
        return redirect()->back();
    }
}
