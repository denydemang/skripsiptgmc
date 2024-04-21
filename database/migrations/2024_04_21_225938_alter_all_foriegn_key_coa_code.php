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
        
        Schema::table('cash_books', function (Blueprint $table) {
            // Membuat foreign key baru atau mengubah yang sudah ada
            $table->foreign('COA_Cash')
                  ->references('code')->on('coa')
                  ->onDelete('restrict') // Mengubah pengaturan ON DELETE
                  ->onUpdate('restrict'); // Mengubah pengaturan ON UPDATE
        });

        Schema::table('advanced_receipts', function (Blueprint $table) {
            // Membuat foreign key baru atau mengubah yang sudah ada
            $table->foreign('coa_debit')
                  ->references('code')->on('coa')
                  ->onDelete('restrict') // Mengubah pengaturan ON DELETE
                  ->onUpdate('restrict'); // Mengubah pengaturan ON UPDATE
                  // Membuat foreign key baru atau mengubah yang sudah ada
            $table->foreign('coa_kredit')
            ->references('code')->on('coa')
            ->onDelete('restrict') // Mengubah pengaturan ON DELETE
            ->onUpdate('restrict'); //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
