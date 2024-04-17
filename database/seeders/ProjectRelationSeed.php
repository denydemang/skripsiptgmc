<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectRelationSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ProjectSeeder::class,
            ProjectDetailSeeder::class,
            ProjectDetailBSeeder::class
        ]);
    }
}
