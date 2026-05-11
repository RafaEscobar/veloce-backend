<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_type_id' => ['sometimes', 'exists:vehicle_types,id'],
            'vehicle_status_id' => ['sometimes', 'exists:vehicle_statuses,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'plates' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'gasoline_type' => ['nullable', 'string', 'max:255'],
            'oil_type' => ['nullable', 'string', 'max:255'],
            'model_name' => ['nullable', 'string', 'max:255'],
            'photo' => ['sometimes', 'image', 'max:2048'],
            'model_year' => ['nullable', 'integer'],
        ];
    }
}
