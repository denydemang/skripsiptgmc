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
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal("total", 65,4)->change();
            $table->decimal("other_fee", 65, 4)->change();
            $table->decimal("percen_ppn", 65, 4)->change();
            $table->decimal("ppn_amount", 65, 4)->change();
            $table->decimal("grand_total", 65, 4)->change();
            $table->decimal("paid_amount", 65,4)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
