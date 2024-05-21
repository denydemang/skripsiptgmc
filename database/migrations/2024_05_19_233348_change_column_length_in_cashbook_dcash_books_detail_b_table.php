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
        Schema::table('cash_books_detail_b', function (Blueprint $table) {
            $table->decimal("debit", 65, 4)->change();
            $table->decimal("credit", 65, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_books_detail_b', function (Blueprint $table) {
            //
        });
    }
};
