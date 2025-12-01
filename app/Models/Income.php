<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    /** @use HasFactory<\Database\Factories\IncomeFactory> */
    use HasFactory;
    protected $fillable = ['user_id','source','amount','date_received','notes'];
    public function user() { return $this->belongsTo(User::class); }
}
