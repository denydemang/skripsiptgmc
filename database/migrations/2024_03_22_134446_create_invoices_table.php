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
        Schema::create('invoices', function (Blueprint $table) {
            $table->string("invoice_no", 50)->primary();
            $table->date("transaction_date");
            $table->string("customer_code", 50);
            $table->string("project_code", 50);
            $table->string("bap_no", 50)->nullable();
            $table->string("bapp_no", 50)->nullable();
            $table->string("spp_no", 50)->nullable();
            $table->string("coa_revenue", 50);
            $table->decimal("total", 8,4);
            $table->decimal("pph_percent", 4,4)->default(0);
            $table->decimal("pph_amount", 4,4)->default(0);
            $table->decimal("percent_ppn", 4,4)->default(0);
            $table->decimal("ppn_amount", 4,4)->default(0);
            $table->decimal("grand_total", 8,4);
            $table->decimal("paid_amount", 4,4);
            $table->text("terbilang");
            $table->text("description")->nullable();
            $table->boolean("is_approve")->default(0);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('customer_code')->references('code')->on('customers')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('project_code')->references('code')->on('projects')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('coa_revenue')->references('code')->on('coa')->onDelete('restrict')->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
