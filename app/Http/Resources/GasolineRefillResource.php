<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GasolineRefillResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'vehicle_id'  => $this->vehicle_id,
            'amount'      => $this->amount,
            'liters'      => $this->liters,
            'date'        => $this->date ? $this->date->format('Y-m-d') : null,
            'gas_station' => $this->gas_station,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
