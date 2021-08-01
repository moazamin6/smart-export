<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_number',
        'carrier_code',
        'order_number',
        'status',
        'sub_status',
        'customer_name',
        'country',
        'item_name',
        'start_date',
        'fulfill_at',
        'order_url',
        'lastUpdateTime',
        'order_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
