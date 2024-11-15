<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecretKey extends Model
{

    protected $fillable = [
        'key',
        'session_name',
        'token',
        'server_host_url',
    ];
}
