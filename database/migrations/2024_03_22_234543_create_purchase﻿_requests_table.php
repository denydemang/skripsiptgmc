<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->string("pr_no", 50)->primary();
            $table->date("transaction_date");
            $table->string("pic_name", 50)->nullable();
            $table->string("division", 50)->nullable();
            $table->string("ref_no", 50)->nullable();
            $table->string("description", 50)->nullable();
            $table->boolean("is_approve")->default(0);
            $table->boolean("is_purchased")->default(0);
            $table->date("date_need");
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
