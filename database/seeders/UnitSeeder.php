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
        Unit::create([
            "code" => "UN_0001",
            "name" => "Laptop",
            "created_by" => now(),
            "updated_by" => now(),

        ]);
        Unit::create([
            "code" => "UN_0002",
            "name" => "VGA",
            "created_by" => now(),
            "updated_by" => now(),

        ]);
        Unit::create([
            "code" => "UN_0003",
            "name" => "RAM",
            "created_by" => now(),
            "updated_by" => now(),

        ]);
        Unit::create([
            "code" => "UN_0004",
            "name" => "Joys stick",
            "created_by" => now(),
            "updated_by" => now(),

        ]);
    }
}
