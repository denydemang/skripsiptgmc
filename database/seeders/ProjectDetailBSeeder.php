<?php

namespace Database\Seeders;

use App\Models\ProjectDetailB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectDetailBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i =0 ; $i <=5 ; $i++){

            ProjectDetailB::create([
                "project_code" => "PRJ240330001",
                "upah_code"=> "UPAH_000$i",
                "unit"=> "Unit_00$i",
                "qty"=>intval(random_int(2,100).$i."635"),
                "price"=>intval(random_int(0,100).$i."446"),
                "total"=> intval(random_int(2,12).$i."10") * intval(random_int(0,12).$i."10"),
            ]);
            
        }
    }
}
