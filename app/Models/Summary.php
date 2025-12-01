<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    /** @use HasFactory<\Database\Factories\SummaryFactory> */
    use HasFactory;
    protected $fillable = ['user_id','week_start','week_end','total_expense_this_week','average_expense'];
    public function user() { return $this->belongsTo(User::class); }
    public function recommendations() { return $this->hasMany(Recommendation::class); }
}
