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
        Schema::table('project_realisations', function (Blueprint $table) {
            $table->decimal("project_amount", 65,4)->change();
            $table->decimal("percent_realisation", 65,4)->change();
            $table->decimal("realisation_amount", 65,4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_realisations', function (Blueprint $table) {
            //
        });
    }
};
