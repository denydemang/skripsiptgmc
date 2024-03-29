<?php

namespace Database\Seeders;

use App\Models\COA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class COASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i =0 ; $i <= 15 ; $i++){

            COA::create([
                'code' => '1.0.1.11.1'.$i,
                'name' => 'Kas Dan Bank'.$i,
                'type' => 'Aktiva',
                'level'=> 2,
                'description' => 'Dsdsfsafdas',
                'beginning_balance' => 12312312,
                'created_by' => 'Admin'
    
            ]);

        }
        
    }
}
