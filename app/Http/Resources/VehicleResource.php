<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'plates' => $this->plates,
            'serial_number' => $this->serial_number,
            'gasoline_type' => $this->gasoline_type,
            'oil_type' => $this->oil_type,
            'model_name' => $this->model_name,
            'model_year' => $this->model_year,
            'photo' => $this->photo
                ? asset(Storage::url($this->photo))
                : null,
            'vehicleType' => $this->whenLoaded('vehicleType'),
            'vehicleStatus' => $this->whenLoaded('vehicleStatus'),
            'gasolineRefills' => $this->whenLoaded('gasolineRefills'),
            'maintenances' => $this->whenLoaded('maintenances'),
            'reminders' => $this->whenLoaded('reminders'),
        ];
    }
}
