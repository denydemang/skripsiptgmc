<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Project;
use App\Models\Type_Project;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeproject = Type_Project::orderBy("code")->first();
        $cust = Customer::orderBy("code")->first();
        Project::create([
            "code" => "PRJ240330001",
            "name" => "Pembangunan Jembatan Suramadu",
            "transaction_date" => Carbon::now()->toDate(),
            "project_type_code" =>  $typeproject->code,
            "customer_code" => $cust->code,
            "location" => "Jawa Tengah",
            "budget" => 150010313.24,
            "start_date" =>  Carbon::now()->addDay(10),
            "end_date" =>  Carbon::now()->addMonth(12),
            "end_date" =>  Carbon::now()->addMonth(12),
            "project_status" =>  0,
            "project_document" =>  'rab.pdf',
            "description" =>  'asdadasdjasdajdaksdjasdkjasdkajsdaksdasdkajsdasdkjasdaksdaskj',
            "created_by" => 'Admin',
        ]);
    }
}
