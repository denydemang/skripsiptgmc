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
        Schema::table('project_detail_realisations', function (Blueprint $table) {
            $table->bigInteger("qty_estimated")->change();
            $table->bigInteger("qty_used")->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_detail_realisations', function (Blueprint $table) {
            //
        });
    }
};
