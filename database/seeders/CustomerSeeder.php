<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'name' => 'Ikhsan Heriyawan',
            'phone' => '082117088123',
        ]);

        Customer::create([
            'name' => 'Qowi Bangsat',
            'phone' => '082117088124',
        ]);
    }
}
