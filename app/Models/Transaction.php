<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sub_method_id',
        'service_id',
        'purpose_id',
    ];


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function sub_method(): BelongsTo
    {
        return $this->belongsTo(SubMethod::class);
    }

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

}
