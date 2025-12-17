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
       Schema::table('financial_insights', function (Blueprint $table) {
        // Menambahkan kolom baru
        $table->decimal('balance', 15, 2)->default(0)->after('total_income');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_insights', function (Blueprint $table) {
            //
        });
    }
};
