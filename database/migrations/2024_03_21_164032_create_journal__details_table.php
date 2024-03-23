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
        Schema::create('journal_details', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("voucher_no", 50);
            $table->text("description")->nullable();
            $table->string("coa_code", 50);
            $table->decimal("debit" , 8 ,4);
            $table->decimal("kredit", 8, 4);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('voucher_no')->references('voucher_no')->on('journals')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('coa_code')->references('code')->on('coa')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_details');
    }
};
