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
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'vehicle_status_id' => ['required', 'exists:vehicle_statuses,id'],
            'name' => ['required', 'string', 'max:255'],
            'plates' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'gasoline_type' => ['nullable', 'string', 'max:255'],
            'oil_type' => ['nullable', 'string', 'max:255'],
            'model_name' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'string'],
            'model_year' => ['nullable', 'integer'],
        ];
    }
}
