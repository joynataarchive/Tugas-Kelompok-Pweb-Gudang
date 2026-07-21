<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'plate_number', 'type', 'status', 'notes'];

    public function loans(): HasMany
    {
        return $this->hasMany(VehicleLoan::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
