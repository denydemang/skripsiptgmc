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
        Schema::table('stocks_out', function (Blueprint $table) {
              // Menghapus foreign key yang ada
            $table->dropForeign(['stock_id']);

            // Menambah foreign key baru dengan pengaturan on update dan on delete yang berbeda
            $table->foreign('stock_id')
                    ->references('id')->on('stocks')
                    ->onUpdate('restrict') // Ubah sesuai kebutuhan Anda
                    ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks_out', function (Blueprint $table) {
            //
        });
    }
};
