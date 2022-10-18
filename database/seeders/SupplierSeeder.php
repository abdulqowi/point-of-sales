<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'name' => 'PT Harum Manis Logistik',
            'address' => 'Jl. fdsalkfdsa fadsfksd',
        ]);
        Supplier::create([
            'name' => 'PT Mudiarno Jasuki Jasa Raharja',
            'address' => 'Jl. kofnsatol jafdraran',
        ]);
    }
}
