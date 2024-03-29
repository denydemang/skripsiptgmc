<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i =0 ; $i <= 15 ; $i++){

            Unit::create([
                "code" => "Unit_00$i",
                "name" => "Laptop",
                "created_by" => now(),
                "updated_by" => now(),
    
            ]);
        }

    }
}
