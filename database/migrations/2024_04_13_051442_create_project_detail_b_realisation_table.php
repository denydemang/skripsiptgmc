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

        Schema::create('project_detail_b_realisations', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("project_code" , 50);
            $table->string("upah_code" , 50);
            $table->string("unit" , 50);
            $table->decimal("qty_estimated" , 50,4);
            $table->decimal("qty_used" , 50, 4);
            $table->decimal("price" , 65, 4);
            $table->decimal("total" , 65, 4);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('project_code')->references('code')->on('projects')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('upah_code')->references('code')->on('upah')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_detail_b_realisations');
    }
};
