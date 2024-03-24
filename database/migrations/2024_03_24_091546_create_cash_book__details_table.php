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
        Schema::create('cash_book_details', function (Blueprint $table) {
            $table->unsignedBigInteger("id")->autoIncrement();
            $table->string("cash_no", 50);
            $table->string("coa", 50);
            $table->text("description", 50);
            $table->decimal("amount", 8,4);
            $table->string("d_k", 5);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('coa')->references('code')->on('COA')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('cash_no')->references('cash_no')->on('cash_books')->onDelete('cascade')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_book_details');
    }
};
