<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'plates' => ['nullable', 'string', 'max:24'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'gasoline_type' => ['nullable', 'string', 'max:255'],
            'oil_type' => ['nullable', 'string', 'max:255'],
            'model_name' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'model_year' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_type_id.required' => 'El campo tipo de vehículo es obligatorio.',
            'vehicle_type_id.exists' => 'El tipo de vehículo seleccionado no existe.',
            'vehicle_status_id.required' => 'El campo estado del vehículo es obligatorio.',
            'vehicle_status_id.exists' => 'El estado del vehículo seleccionado no existe.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no debe superar los 255 caracteres.',
            'plates.string' => 'El campo placas debe ser una cadena de texto.',
            'plates.max' => 'El campo placas no debe superar los 24 caracteres.',
            'serial_number.string' => 'El campo número de serie debe ser una cadena de texto.',
            'serial_number.max' => 'El campo número de serie no debe superar los 255 caracteres.',
            'gasoline_type.string' => 'El campo tipo de gasolina debe ser una cadena de texto.',
            'gasoline_type.max' => 'El campo tipo de gasolina no debe superar los 255 caracteres.',
            'oil_type.string' => 'El campo tipo de aceite debe ser una cadena de texto.',
            'oil_type.max' => 'El campo tipo de aceite no debe superar los 255 caracteres.',
            'model_name.string' => 'El campo modelo debe ser una cadena de texto.',
            'model_name.max' => 'El campo modelo no debe superar los 255 caracteres.',
            'photo.image' => 'El campo foto debe ser una imagen.',
            'photo.max' => 'El campo foto no debe superar los 2 MB.',
            'model_year.integer' => 'El campo año del modelo debe ser un número entero.',
        ];
    }
}
