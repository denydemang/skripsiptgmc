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
        Schema::create('projects', function (Blueprint $table) {
            $table->string("code", 50)->primary();
            $table->string("name", 50);
            $table->string("project_type_code", 50);
            $table->string("customer_code", 50);
            $table->string("location", 50);
            $table->decimal("budget", 8, 4);
            $table->date("start_date");
            $table->date("end_date");
            $table->boolean("project_status")->default(0);
            $table->string("project_document", 150);
            $table->text("description");
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('project_type_code')->references('code')->on('type_projects')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('customer_code')->references('code')->on('customers')->onDelete('restrict')->onUpdate("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
