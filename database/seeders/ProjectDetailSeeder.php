<?php

namespace Database\Seeders;

use App\Models\Project_Detail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i =0 ; $i <= 8 ; $i++){

            Project_Detail::create([
                "project_code" => 'PRJ240330001',
                "item_code" => "ITEM_0$i",
                "unit_code" => "Unit_00$i",
                "qty" => intval(random_int(2,100).$i. "999"),
                "created_by" => "admin",                
            ]);
        }
    }
}
