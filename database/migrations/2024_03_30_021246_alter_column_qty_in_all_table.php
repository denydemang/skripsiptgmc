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
            $table->decimal("qty",50 ,4)->change();
        });
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal("actual_stock",50 ,4)->change();
            $table->decimal("used_stock",50 ,4)->change();
        });
        Schema::table('purchase_request_details', function (Blueprint $table) {
            $table->decimal("qty",50 ,4)->change();
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal("qty",50 ,4)->change();
        });
        Schema::table('project_detail_realisations', function (Blueprint $table) {
            $table->decimal("qty_estimated",50 ,4)->change();
            $table->decimal("qty_used",50 ,4)->change();
        });
        Schema::table('items', function (Blueprint $table) {
            $table->decimal("min_stock",50 ,4)->change();
            $table->decimal("max_stock",50 ,4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all', function (Blueprint $table) {
            //
        });
    }
};
