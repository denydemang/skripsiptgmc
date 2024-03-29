<?php

namespace Database\Seeders;

use App\Models\Upah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i =0 ; $i <= 15 ; $i++){

            Upah::create([
                "code" => "UPAH_000$i",
                "job" => "Ngarit Mancak mancak$i",
                "description" => "Test Description$i",
                "unit" => "hour",
                "price" => 2343121,
                "coa_code" => '1.0.1.11.1'.$i,
                "created_by" =>"admin",
                "updated_by" => "admin",
    
            ]);
        }
    }
}
