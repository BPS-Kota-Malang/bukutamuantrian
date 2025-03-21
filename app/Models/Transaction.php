<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

// use TransactionStatusHistory;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sub_method_id',
        'service_id',
        'purpose_id',
        'status', // Add this!
        'status_changed_at', // Add this if needed
    ];

    protected static function boot()
    {
        parent::boot();

        // static::updating(function ($transaction) {
        //     if ($transaction->isDirty('status')) { // Only track status changes
        //         // Log the old status and new status in history
        //         TransactionStatusHistory::create([
        //             'transaction_id' => $transaction->id,
        //             'old_status' => $transaction->getOriginal('status'),
        //             'new_status' => $transaction->status,
        //             'changed_at' => now(),
        //         ]);

        //         // Update the latest status_changed_at timestamp
        //         $transaction->status_changed_at = now();
        //     }
        // });

        static::updating(function ($transaction) {
            if ($transaction->isDirty('status')) { // Only track status changes
                try {
                    Log::info('Status is being updated', [
                        'old_status' => $transaction->getOriginal('status'),
                        'new_status' => $transaction->status,
                    ]);

                    TransactionStatusHistory::create([
                        'transaction_id' => $transaction->id,
                        'old_status' => $transaction->getOriginal('status'),
                        'new_status' => $transaction->status,
                        'changed_at' => now(),
                    ]);

                    $transaction->status_changed_at = now();
                } catch (\Exception $e) {
                    Log::error('Error updating transaction status history: ' . $e->getMessage());
                }
            }
        });
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(TransactionStatusHistory::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function purpose(): BelongsTo
    {
        return $this->belongsTo(Purpose::class);
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
