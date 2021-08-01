<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('days','created_at','updated_at');
    }
}
