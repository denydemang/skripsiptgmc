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
        Schema::create('stocks_out', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("ref_no", 100);
            $table->string("item_code", 50);
            $table->string("unit_code", 50);
            $table->dateTime("item_date");
            $table->integer("qty");
            $table->decimal("cogs", 8, 4);
            $table->unsignedBigInteger("stock_id");
            $table->char('created_by' , 50)->nullable();
            $table->char('updated_by' , 50)->nullable();
            $table->timestamps();
            $table->foreign('item_code')->references('code')->on('items')->onDelete('restrict')->onUpdate("cascade");
            $table->foreign('unit_code')->references('code')->on('units')->onDelete('restrict')->onUpdate("restrict");
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade')->onUpdate("cascade");
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_out');
    }
};
