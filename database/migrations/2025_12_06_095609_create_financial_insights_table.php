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
        Schema::create('financial_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Identitas Laporan
            $table->enum('type', ['weekly', 'monthly']); 
            $table->string('period_key'); // Format: "2025-12" (Bulanan) atau "2025-W49" (Mingguan)
            
            // Snapshot Data (Matematika)
            $table->decimal('total_expense', 15, 2)->default(0);
            $table->decimal('total_income', 15, 2)->default(0);
            $table->decimal('percentage_change', 8, 2)->nullable(); // Tren (+/-)
            
            // Hasil AI (Dipisah agar rapi)
            $table->enum('status', ['safe', 'warning', 'danger'])->default('safe');
            $table->text('ai_analysis')->nullable();       // Paragraf analisis
            $table->text('ai_recommendation')->nullable(); // Poin-poin saran
            
            // Data Pendukung Visual (JSON)
            $table->json('wasteful_dates')->nullable(); // Tanggal boros untuk kalender
            
            $table->timestamps();
            
            // Mencegah duplikat laporan di periode yang sama
            $table->unique(['user_id', 'type', 'period_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_insights');
    }
};
