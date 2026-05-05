<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GasolineRefill extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'amount',
        'liters',
        'date',
        'gas_station',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'liters' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
