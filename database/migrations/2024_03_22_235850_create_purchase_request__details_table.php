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
        Schema::create('purchase_request_details', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("pr_no", 50); 
            $table->string("item_code", 50); 
            $table->string("unit_code", 50); 
            $table->unsignedBigInteger("qty"); 
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('pr_no')->references('pr_no')->on('purchase_requests')->onDelete('cascade')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_details');
    }
};
