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
        Schema::create('cash_books', function (Blueprint $table) {
            $table->string("cash_no", 50)->primary();
            $table->date("transaction_date");
            $table->string("COA_Cash", 50);
            $table->string("ref", 50);
            $table->decimal("total_transaction", 8, 4);
            $table->text("description");
            $table->string("d_k", 5);
            $table->boolean("is_approve")->default(0);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('COA_Cash')->references('code')->on('COA')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_books');
    }
};
