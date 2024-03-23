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
        Schema::create('purchases', function (Blueprint $table) {
            $table->string("purchase_no", 50)->primary();
            $table->string("pr_no", 50);
            $table->date("transaction_date");
            $table->string("supplier_code", 50);
            $table->decimal("total", 8,4);
            $table->decimal("other_fee", 8, 4);
            $table->decimal("percen_ppn", 4, 4);
            $table->decimal("ppn_amount", 4, 4);
            $table->decimal("grand_total", 8, 4);
            $table->string("payment_term_code", 50);
            $table->boolean("is_credit")->default(1);
            $table->boolean("is_approve")->default(0);
            $table->decimal("paid_amount")->default(0);
            $table->text("description");
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('pr_no')->references('pr_no')->on('purchase_requests')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('supplier_code')->references('code')->on('suppliers')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('payment_term_code')->references('code')->on('payment_terms')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
