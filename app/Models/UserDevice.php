<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'device_browser',
        'device_os',
        'device_resolution',
        'status',
    ];
    
}
