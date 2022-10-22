<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderDetail, Product};
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $year = ['01','02','03','04','05','06', '07','08','09','10','11','12'];

        $income = [];
        $expend = [];
        foreach ($year as $value) {
            $pemasukan = Order::with('order_details')
                ->where(DB::raw("DATE_FORMAT(date, '%m')"), $value)
                ->whereNotNull('customer_id')
                ->get();
            $pengeluaran = Order::with('order_details')
                ->where(DB::raw("DATE_FORMAT(date, '%m')"), $value)
                ->whereNotNull('supplier_id')
                ->get();
            $income[] = $pemasukan->sum('total_price');
            $expend[] = $pengeluaran->sum('total_price');
        }

        $chartjs = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'])
        ->datasets([
            [
                "label" => "Pemasukan",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => $income,
            ],
            [
                "label" => "Pengeluaran",
                'backgroundColor' => "rgb(255, 0, 0, 0.31)",
                'borderColor' => "rgba(255, 0, 0, 0.7)",
                "pointBorderColor" => "rgba(255, 0, 0, 0.7)",
                "pointBackgroundColor" => "rgba(255, 0, 0, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => $expend,
            ]
        ])
        ->options([]);

        $bar = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->size(['width' => 400, 'height' => 200])
         ->labels(['Label x', 'Label y'])
         ->datasets([
             [
                 "label" => "My First dataset",
                 'backgroundColor' => ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                 'data' => [69, 59]
             ],
             [
                 "label" => "My First dataset",
                 'backgroundColor' => ['rgba(255, 99, 132, 0.3)', 'rgba(54, 162, 235, 0.3)'],
                 'data' => [65, 12]
             ]
         ])
         ->options([]);

         $pie = app()->chartjs
        ->name('pieChartTest')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Label x', 'Label y'])
        ->datasets([
            [
                'backgroundColor' => ['#FF6384', '#36A2EB'],
                'hoverBackgroundColor' => ['#FF6384', '#36A2EB'],
                'data' => [69, 59]
            ]
        ])
        ->options([]);

        return view('dashboard', [
            'orders' => Order::get(),
            'products' => Product::get(),
            'chartjs' => $chartjs,
            'bar' => $bar,
            'pie' => $pie,
        ]);
    }
}
