<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeechAudio extends Model
{
    protected $fillable = [
        'text',
        'filename',
        'audiopath'
    ];
}
