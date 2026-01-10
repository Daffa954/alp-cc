<?php

namespace App\Models;

use App\Traits\HashableId;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HashableId;
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'activity_id', 'category', 'description', 'amount', 'date'];
    protected $casts = [
        'date' => 'date',  // <-- Ini memberitahu Laravel: "Tolong kolom date otomatis jadi objek Carbon"
        'amount' => 'integer',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
