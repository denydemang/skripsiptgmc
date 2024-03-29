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
        for ($i =0 ; $i <= 15 ; $i++){

            Item::create([
                "code" => "ITEM_0$i",
                "name" => "ASUS$i",
                "unit_code" => "Unit_00$i",
                "min_stock" => 5,
                "max_stock" => 20,
                "category_code" => "CTG_00$i",
                "created_by" => now(),
                "updated_by" => now(),
            ]);
        }

    }
}
