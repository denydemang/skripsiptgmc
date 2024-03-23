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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("purchase_no", 50);
            $table->string("item_code", 50);
            $table->string("unit_code", 50);
            $table->unsignedBigInteger("qty");
            $table->decimal("price", 8,4);
            $table->decimal("total", 8,4);
            $table->decimal("discount", 8,4);
            $table->decimal("sub_total", 8,4);
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('purchase_no')->references('purchase_no')->on('purchases')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('item_code')->references('code')->on('items')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('unit_code')->references('code')->on('units')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
