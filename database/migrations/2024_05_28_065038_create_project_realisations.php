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
        Schema::create('project_realisations', function (Blueprint $table) {
            $table->string("code", 50)->primary();
            $table->string("project_code", 50);
            $table->string("customer_code", 50);
            $table->date("realisation_date");
            $table->text("description");
            $table->decimal("project_amount", 8, 4);
            $table->decimal("percent_realisation", 8, 4);
            $table->decimal("realisation_amount", 8, 4);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('project_code')->references('code')->on('projects')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('customer_code')->references('code')->on('customers')->onDelete('restrict')->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_realisations');
    }
};
