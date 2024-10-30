<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'number',
        'transaction_id',
        'operator_id',
        'status',
    ];

    public function  transaction() : BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }


}
