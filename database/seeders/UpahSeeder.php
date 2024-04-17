<?php

namespace Database\Seeders;

use App\Models\Upah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path("dump_sql/upah.sql");
        DB::unprepared(file_get_contents($path));
    }
}
