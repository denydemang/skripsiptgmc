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
            "created_by" => 'admin',
            "updated_by" => 'admin',

        ]);
        Unit::create([
            "code" => "UN_0002",
            "name" => "VGA",
            "created_by" => 'admin',
            "updated_by" => 'admin',

        ]);
        Unit::create([
            "code" => "UN_0003",
            "name" => "RAM",
            "created_by" => 'admin',
            "updated_by" => 'admin',

        ]);
        Unit::create([
            "code" => "UN_0004",
            "name" => "Joys stick",
            "created_by" => 'admin',
            "updated_by" => 'admin',

        ]);

        $supplyModel = Unit::orderBy("code", "desc")->lockForUpdate()->first();
        Unit::create([
            "code" => automaticCode_H("UN_0" ,$supplyModel, false,"code"),
            "name" => "Mouse",
            "created_by" => 'admin',
            "updated_by" => 'admin',

        ]);
    }
}
