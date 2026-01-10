<?php

namespace App\Models;

use App\Traits\HashableId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialInsight extends Model
{
    
    use HasFactory;
    use HashableId;

    /**
     * Kolom yang boleh diisi (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'type',              // 'weekly' atau 'monthly'
        'period_key',        // '2025-12'
        'total_expense',
        'total_income',
        'balance',
        'percentage_change', // Tren kenaikan/penurunan (%)
        'status',            // 'safe', 'warning', 'danger'
        'ai_analysis',       // Teks analisis kondisi
        'ai_recommendation', // Teks saran aksi
        'wasteful_dates',    // JSON daftar tanggal boros
    ];

    /**
     * Konversi otomatis tipe data saat diambil dari database.
     */
    protected $casts = [
        // PENTING: Mengubah JSON di database menjadi Array PHP otomatis
        'wasteful_dates' => 'array', 
        'ai_recommendation' => 'array', //
        // Memastikan format angka desimal konsisten
        'total_expense' => 'decimal:2',
        'total_income' => 'decimal:2',
        'percentage_change' => 'decimal:2',
    ];

    /**
     * Relasi ke User pemilik laporan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}