<?php

namespace App\Http\Controllers;

use App\Models\{Supplier, OrderDetail};
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\{Order, Product, Customer};

class OrderSaleController extends Controller
{
    public function sales()
    {
        if (request()->ajax()) {
            $orders = Order::whereNotNull('customer_id')->latest()->get();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->editColumn('customer_id', function (Order $order) {
                    return $order->customer->name;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="btn-group">
                            <a class="badge bg-navy dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" id="showSales" class="btn btn-sm btn-primary">View</a>
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm" id="editProduct">Print</a>
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
        return view('orders.sales.index', [
            'orders' => Order::whereNotNull('customer_id')->get(),
        ]);
    }

    public function createSales()
    {
        $record = Order::latest()->first();
        if (isset($record)) {
            $expNum = explode('-', $record->order_number);
            $nextInvoiceNumber = $expNum[0] . '-' . $expNum[1] . '-' . ($expNum[2] + '1');
        } else {
            $nextInvoiceNumber = 'INV-' . date('dmy') . '-10001';
        }
        return view('orders.sales.create', [
            'products' => Product::all(),
            'customers' => Customer::all(),
            'orderNumber' => $nextInvoiceNumber,
        ]);
    }

    public function storeSales()
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
                    'customer_id' => request('customer_id'),
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
}
