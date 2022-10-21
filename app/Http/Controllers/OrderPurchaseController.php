<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use LaravelDaily\Invoices\Facades\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\{Buyer, Party};
use App\Models\{Order, Product, Supplier, OrderDetail};

class OrderPurchaseController extends Controller
{
    public function purchases()
    {
        if (request()->ajax()) {
            $orders = Order::whereNotNull('supplier_id')->with('supplier')->latest()->get();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->editColumn('supplier_id', function (Order $order) {
                    return $order->supplier->name;
                })
                ->editColumn('status', function (Order $order) {
                    $paid = '<form action="'.route('sales.status', $order->id).'" method="post">'.csrf_field().'<input type="hidden" name="status" value="pending"><button type="submit" class="btn btn-sm btn-primary">Paid</button></form>';
                    $pending = '<form action="'.route('sales.status', $order->id).'" method="post">'.csrf_field().'<input type="hidden" name="status" value="paid"><button type="submit" class="btn btn-sm btn-warning">Pending</button></form>';
                    return $order->status == 'paid' ? $paid : $pending;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="btn-group">
                            <a class="badge bg-navy dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" id="showPurchase" class="btn btn-sm btn-primary">View</a>
                                <a class="dropdown-item" href="' . route('purchases.print',$row->id) . '" class="btn btn-primary btn-sm" id="editProduct">Print</a>
                                <form action=" ' . route('purchases.destroy', $row->id) . '" method="POST">
                                    <button type="submit" class="dropdown-item" onclick="return confirm(\'Apakah yakin ingin menghapus ini?\')">Hapus</button>
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                </form>
                            </div>
                        </div>';
                    return $btn;
                })
                ->rawColumns(['checkbox', 'action', 'status'])
                ->make(true);
        }
        return view('orders.purchases.index', [
            'orders' => Order::whereNotNull('supplier_id')->get(),
        ]);
    }

    public function createPurchases()
    {
        // Create Code Invoice
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
            'orderNumber' => $nextInvoiceNumber,
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
        // Delete product quantity
        foreach ($orderDetail as $value) {
            Product::where('id', $value->product_id)->update([
                'quantity' => DB::raw("quantity-$value->quantity")
            ]);
        }
        $order->delete();
        return redirect()->back();
    }

    public function showPurchases($id)
    {
        $order = Order::with('order_details')->find($id);
        return response()->json($order);
    }

    public function printPurchases($id)
    {
        $orders = Order::with('supplier', 'order_details')->find($id);
        $supplier = new Buyer([
            'name' => $orders->supplier->name,
            'custom_fields' => [
                'nomor telepon' => $orders->supplier->phone,
            ],
        ]);
        $client = new Party([
            'name'          => 'Toko dulQowi Jaya Baru',
            'phone'         => '(520) 318-9486',
            'custom_fields' => [
                'note'        => 'Terima Kasih Sudah Berbelanja Di Kami',
                'address'     => 'Desa Kempek Blok 3 Penangisan Gempol',
                'business id' => '365#GG',
            ],
        ]);
        $item = [];

        foreach ($orders->order_details as $value) {
            $item[] = (new InvoiceItem())->title($value->product_name)->pricePerUnit($value->price)->quantity($value->quantity);
        }

        $invoice = Invoice::make('Struk')
            ->seller($client)
            ->series(substr($orders->order_number, 0, -6))
            ->status($orders->status)
            ->buyer($supplier)
            ->currencySymbol('Rp. ')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->filename($client->name . ' ' . $supplier->name)
            ->addItems($item);

        return $invoice->stream();
    }
}
