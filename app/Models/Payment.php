<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'registration_id',
        'order_id',
        'amount',
        'status'
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function registration() {
        return $this->belongsTo(Registration::class);
    }
}
