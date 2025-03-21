<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'old_status', 'new_status', 'changed_at'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

