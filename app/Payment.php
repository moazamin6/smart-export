<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_type', 'payment_id', 'amount', 'next_payment_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
