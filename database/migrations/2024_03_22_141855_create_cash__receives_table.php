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
        Schema::create('cash_receives', function (Blueprint $table) {
            $table->string("bkm_no", 50)->primary();
            $table->string("transaction_date");
            $table->string("customer_code");
            $table->string("ref_no");
            $table->string("coa_cash_code");
            $table->decimal("total_amount",8,4);
            $table->string("terbilang")->nullable();
            $table->boolean("is_approve")->default(0);
            $table->string("description")->nullable();
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('customer_code')->references('code')->on('customers')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('coa_cash_code')->references('code')->on('coa')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_receives');
    }
};
