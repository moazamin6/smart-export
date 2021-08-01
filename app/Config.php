<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Config extends Model
{

    protected $fillable = [
        'user_id', 'type', 'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function saveConfig($type, $value)
    {
        $user = Auth::user();
        $where = [
            'user_id' => $user->id,
            'type' => $type
        ];
        $data = [
            'type' => $type,
            'value' => $value
        ];
        $user->configs()->updateOrCreate($where, $data);
    }

    public static function getConfig($user_id, $type)
    {
        $user = User::find($user_id);
        $where = [
            'type' => $type
        ];
        return $config = $user->configs()->where($where)->first();
    }
}
