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
        $path = public_path("dump_sql/coa.sql");
        DB::unprepared(file_get_contents($path));

    }
}
