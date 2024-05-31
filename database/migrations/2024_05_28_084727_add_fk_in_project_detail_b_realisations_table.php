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
        Schema::table('project_detail_b_realisations', function (Blueprint $table) {
            $table->foreign('project_realisation_code')->references('code')->on('project_realisations')->onDelete('cascade')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_detail_b_realisations', function (Blueprint $table) {
            //
        });
    }
};
