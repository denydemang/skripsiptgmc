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
        Schema::create('project_detail_realisations', function (Blueprint $table) {
            $table->string("code", 50)->primary();
            $table->string("project_code", 50);
            $table->string("item_code", 50);
            $table->string("unit_code", 50);
            $table->string("qty_estimated", 50);
            $table->string("qty_used", 50);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('project_code')->references('code')->on('projects')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('item_code')->references('code')->on('items')->onDelete('restrict')->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_detail_realisations');
    }
};
