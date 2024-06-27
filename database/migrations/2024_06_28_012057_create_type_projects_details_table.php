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
        Schema::create('type_projects_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("type_project_code", 50);
            $table->string("item_code", 50);
            $table->string("unit_code",50);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->foreign('type_project_code')->references('code')->on('type_projects')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('item_code')->references('code')->on('items')->onDelete('restrict')->onUpdate("restrict");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_projects_details');
    }
};
