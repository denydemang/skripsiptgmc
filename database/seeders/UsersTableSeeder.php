<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "username" => "admin",
            "name" => "Super Admin",
            "password" => Hash::make("admin123"),
            "id_role" => 1,
            "active_status" => 1,
            "created_by" => "admin",

        ]);
    }
}
