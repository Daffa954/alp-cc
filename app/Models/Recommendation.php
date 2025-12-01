<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    /** @use HasFactory<\Database\Factories\RecommendationFactory> */
    use HasFactory;
    protected $fillable = ['user_id','summary_id','week_start','message'];
    public function user() { return $this->belongsTo(User::class); }
    public function summary() { return $this->belongsTo(Summary::class); }
}
