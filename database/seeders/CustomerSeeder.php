<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $limit = 10;

        for ($i=1; $i <=$limit; $i++) { 
            Customer::updateOrCreate([
                "code" => "CATE_00".$i,
                "name" => "Customer".$i,
                "address" => "pandean",
                "zip_code" => "",
                "npwp" => "",
                "email" => "customer".$i."@lara.com",
                "phone" => "0878767676".$i,
                "coa_code" => "COA_3000",
                "created_by" => User::find('admin')->name,
                "updated_by" => User::find('admin')->name,
            ]);
        }
    }
}
