<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDevice extends Model
{
    protected $fillable = ['user_id', 'device_identifier', 'device_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
