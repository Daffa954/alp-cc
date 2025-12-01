<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;

    protected $fillable = ['user_id','title','start_longitude','start_latitude','end_longitude','end_latitude','distance_in_km','transportation','cost_to_there','activity_location','date_start','date_end'];

    public function user() { return $this->belongsTo(User::class); }
    public function expenses() { return $this->hasMany(Expense::class); }
}
