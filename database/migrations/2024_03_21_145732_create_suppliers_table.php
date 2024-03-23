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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->string("code", 50)->primary();
            $table->string("name", 100);
            $table->text("address");
            $table->text("zip_code")->nullable();
            $table->string("npwp",50)->nullable();
            $table->string("email", 50)->nullable();
            $table->string("phone", 50);
            $table->string("coa_code", 100);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('coa_code')->references('code')->on('coa')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
