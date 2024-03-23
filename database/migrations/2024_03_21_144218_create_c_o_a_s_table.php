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
        Schema::create('coa', function (Blueprint $table) {
            $table->string("code", 100)->primary();
            $table->string("name", 100);
            $table->string("type", 50);
            $table->string("level", 50);
            $table->string("description", 100)->nullable();
            $table->decimal("beginning_balance", 8, 4)->default(0);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
