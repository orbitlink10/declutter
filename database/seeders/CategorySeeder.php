<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Vehicles',
            'Property',
            'Phones & Tablets',
            'Electronics',
            'Home, Furniture & Appliances',
            'Fashion',
            'Beauty & Personal Care',
            'Services',
        ];

        foreach ($categories as $name) {
            Category::query()->firstOrCreate(['name' => $name]);
        }
    }
}
