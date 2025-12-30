<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['user_id','activity_id','category','description','amount', 'date'];

    public function user() { return $this->belongsTo(User::class); }
    public function activity() { return $this->belongsTo(Activity::class); }
}
