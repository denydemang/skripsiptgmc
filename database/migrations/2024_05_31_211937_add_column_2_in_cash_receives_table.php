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
        Schema::table('cash_receives', function (Blueprint $table) {
            $table->decimal('cash_amount' ,65, 4 )->default(0)->after('coa_cash_code');
            $table->decimal('deposit_amount' ,65, 4 )->default(0)->after('cash_amount');
            $table->string('approved_by' ,100)->nullable()->after('is_approve');
            $table->string('received_via' ,50)->nullable()->after('ref_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_receives', function (Blueprint $table) {
            //
        });
    }
};
