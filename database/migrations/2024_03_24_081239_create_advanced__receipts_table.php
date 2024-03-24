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
        Schema::create('advanced_receipts', function (Blueprint $table) {
            $table->string("adr_no", 50)->primary();
            $table->date("transaction_date");
            $table->string("customer_code",50);
            $table->string("coa_debit", 50);
            $table->string("coa_kredit", 50);
            $table->decimal("deposit_amount", 8,4);
            $table->decimal("deposit_allocation", 8,4);
            $table->text("description")->nullable();
            $table->boolean("is_approve")->default(0);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('customer_code')->references('code')->on('customers')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('coa_debit')->references('code')->on('COA')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('coa_kredit')->references('code')->on('COA')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advanced_receipts');
    }
};
