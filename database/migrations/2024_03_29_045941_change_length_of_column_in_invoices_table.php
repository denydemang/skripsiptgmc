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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal("total", 65,4)->change();
            $table->decimal("pph_percent", 65,4)->default(0)->change();
            $table->decimal("pph_amount", 65,4)->default(0)->change();
            $table->decimal("percent_ppn", 65,4)->default(0)->change();
            $table->decimal("ppn_amount", 65,4)->default(0)->change();
            $table->decimal("grand_total", 65,4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
