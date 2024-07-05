<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\COA;
use App\Models\TypeProjectDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            UsersTableSeeder::class,
            COASeeder::class,
            UpahSeeder::class,
            JournalTypeSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            ItemSeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
            PaymentTermSeeder::class,
            TypeProjectSeeder::class,
            TypeProjectDetailSeeder::class,
            TypeProjectDetailBSeeder::class,
            
        ]);
    }
}
