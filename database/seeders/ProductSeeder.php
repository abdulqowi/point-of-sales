<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for($i = 0; $i < 1000; $i++) {
            Product::create([
                'name' => $faker->name,
                'price' => rand(1000,1220000),
                'quantity' => 0,
                'category_id' => rand(1,13),
            ]);
    }
    }











}
