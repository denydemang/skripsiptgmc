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
        Schema::create('payments', function (Blueprint $table) {
            $table->string("bkk_no", 50)->primary();
            $table->date("transaction_date");
            $table->string("supplier_code", 50);
            $table->string("ref_no", 50);
            $table->string("coa_cash_code", 50);
            $table->decimal("total_amount", 8, 4); 
            $table->string("terbilang", 50)->nullable(); 
            $table->boolean("is_approve", 50)->default(0); 
            $table->text("description")->nullable(); 
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('supplier_code')->references('code')->on('suppliers')->onDelete('restrict')->onUpdate("restrict");
            $table->foreign('coa_cash_code')->references('code')->on('coa')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
