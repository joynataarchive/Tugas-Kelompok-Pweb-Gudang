<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'user_id', 'purpose', 'borrowed_at', 'returned_at', 'status', 'notes',
    ];

    protected $casts = [
        'borrowed_at'  => 'datetime',
        'returned_at'  => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }
}
