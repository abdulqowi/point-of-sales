<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = 
        ['name' => 'Snack', 'Frozen Food', 'Sembako', 'Kecantikan', 'ATK', 'Obat', 'Minuman','Makanan Instant','Es krim','Rokok','Roti','Permen','Pet Food'];
        foreach ($categories as $category)
        Category::create([
            'name' => $category,
        ]
        );
        
    }
}
