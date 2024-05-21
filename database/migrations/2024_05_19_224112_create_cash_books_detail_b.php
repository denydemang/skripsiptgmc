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
        Schema::create('cash_books_detail_b', function (Blueprint $table) {
            $table->string("cash_no", 50)->primary();
            $table->date("transaction_date");
            $table->string("COA", 50);
            $table->string("ref", 50);
            $table->decimal("debit", 8, 4);
            $table->decimal("credit", 8, 4);
            $table->text("description");
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('COA')->references('code')->on('COA')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('cash_no')->references('cash_no')->on('cash_books')->onDelete('cascade')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_books_detail_b');
    }
};
