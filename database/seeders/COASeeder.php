<?php

namespace Database\Seeders;

use App\Models\COA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class COASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'public/coa.sql';
        DB::unprepared(file_get_contents($path));
        
    }
}
