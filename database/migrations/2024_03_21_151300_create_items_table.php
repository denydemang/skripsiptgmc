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
        Schema::create('items', function (Blueprint $table) {
            $table->string("code", 50)->primary();
            $table->string("name", 100);
            $table->string("unit_code", 50);
            $table->integer("min_stock");
            $table->integer("max_stock");
            $table->string("category_code", 50);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('unit_code')->references('code')->on('units')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('category_code')->references('code')->on('categories')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
