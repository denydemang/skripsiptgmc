<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            "code" => "I_01",
            "name" => "ASUS",
            "unit_code" => "UN_0001",
            "min_stock" => 5,
            "max_stock" => 20,
            "category_code" => "CATE_001",
            "created_by" => now(),
            "updated_by" => now(),
        ]);
        Item::create([
            "code" => "I_02",
            "name" => "ADATA",
            "unit_code" => "UN_0003",
            "min_stock" => 3,
            "max_stock" => 10,
            "category_code" => "CATE_002",
            "created_by" => now(),
            "updated_by" => now(),
        ]);
        Item::create([
            "code" => "I_03",
            "name" => "GEFORCE RTX",
            "unit_code" => "UN_0002",
            "min_stock" => 8,
            "max_stock" => 25,
            "category_code" => "CATE_003",
            "created_by" => now(),
            "updated_by" => now(),
        ]);
    }
}
