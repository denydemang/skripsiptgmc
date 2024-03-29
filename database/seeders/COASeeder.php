<?php

namespace Database\Seeders;

use App\Models\COA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class COASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        COA::create([
            "code" => "COA_1000",
            "name" => "KAS",
            "type" => "Aset",
            "level" => "1",
            "description" => "Uang tunai di kas",
            "beginning_balance" => '1.7575',
            "created_by" => now(),
            "updated_by" => now(),
        ]);

        COA::create([
            "code" => "COA_3000",
            "name" => "Modal Pemilik",
            "type" => "Ekuitas",
            "level" => "2",
            "description" => "Investasi pemilik dalam bisnis",
            "beginning_balance" => '1.6575',
            "created_by" => now(),
            "updated_by" => now(),
        ]);

        COA::create([
            "code" => "COA_1100",
            "name" => "Bank",
            "type" => "Aset",
            "level" => "2",
            "description" => "Saldo di rekening bank",
            "beginning_balance" => '7.5555',
            "created_by" => now(),
            "updated_by" => now(),
        ]);

    }
}
