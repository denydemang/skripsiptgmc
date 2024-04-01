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
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal("price",65 ,4)->change();
            $table->decimal("total", 65,4)->change();
            $table->decimal("discount", 65,4)->change();
            $table->decimal("sub_total", 65,4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            //
        });
    }
};
