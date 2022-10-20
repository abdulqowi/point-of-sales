<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

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
                'selling_price' => rand(10000,100000),
                'purchase_price' => rand(10000,100000),
                'quantity' => 0,
                'category_id' => rand(1,13),
            ]);
    }
    }











}
