<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubMethod extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'method_id',
    ];

    public function Method() : BelongsTo
    {
        return $this->belongsTo(Method::class);
    }

}
