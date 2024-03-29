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
        for ($i =0 ; $i <= 15 ; $i++){
            Category::create([
                "code" => "CTG_00$i",
                "name" => "Barang",
                "coa_code" => '1.0.1.11.1'.$i,
                "created_by" => 'Admin',
            ]);
            
        }
    }
}
