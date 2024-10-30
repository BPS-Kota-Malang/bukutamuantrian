<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'age',
        'work_id',
        'education_id',
        'university_id',
        'institution_id'
    ];

    public static function normalizePhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters
        $normalized = preg_replace('/\D/', '', $phoneNumber);

        // If the phone number starts with "0", replace it with "62"
        if (substr($normalized, 0, 1) === '0') {
            $normalized = '62' . substr($normalized, 1);
        }

        return $normalized;
    }


    public function work() : BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
