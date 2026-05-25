<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'name' => $this->name,
            'date' => $this->date?->format('Y-m-d'),
            'cost' => $this->cost,
            'is_reminder_enabled' => $this->is_reminder_enabled,
            'notes' => $this->notes,
        ];
    }
}
