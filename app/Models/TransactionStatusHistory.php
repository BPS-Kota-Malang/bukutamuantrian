<?php

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionStatusHistory extends Model
{
    protected $fillable = ['transaction_id', 'old_status', 'new_status', 'changed_at'];

    public $timestamps = false;

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
