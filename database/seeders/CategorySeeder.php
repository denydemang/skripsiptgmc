<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            "code" => "CATE_001",
            "name" => "Barang",
            "coa_code" => "COA_3000",
            "created_by" => now(),
            "updated_by" => now(),
        ]);
        Category::create([
            "code" => "CATE_002",
            "name" => "Simpanan",
            "coa_code" => "COA_1100",
            "created_by" => now(),
            "updated_by" => now(),
        ]);
        Category::create([
            "code" => "CATE_003",
            "name" => "Patungan",
            "coa_code" => "COA_1000",
            "created_by" => now(),
            "updated_by" => now(),
        ]);
    }
}
