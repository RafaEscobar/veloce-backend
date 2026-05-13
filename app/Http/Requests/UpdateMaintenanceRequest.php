<?php
/*
|--------------------------------------------------------------------------
| UpdateMaintenanceRequest
|--------------------------------------------------------------------------
|
| Este archivo valida los datos al actualizar un registro de mantenimiento.
|
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id'          => ['sometimes', 'exists:vehicles,id'],
            'name'                => ['sometimes', 'string', 'max:255'],
            'date'                => ['sometimes', 'date'],
            'cost'                => ['nullable', 'numeric', 'min:0'],
            'is_reminder_enabled' => ['nullable', 'boolean'],
            'notes'               => ['nullable', 'string'],
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas definidas.
     */
    public function messages(): array
    {
        return [
            'vehicle_id.exists'    => 'El vehículo seleccionado no es válido.',
            'name.string'          => 'El nombre debe ser una cadena de texto.',
            'name.max'             => 'El nombre no debe superar los 255 caracteres.',
            'date.date'            => 'La fecha no tiene un formato válido.',
            'cost.numeric'         => 'El costo debe ser un número.',
            'cost.min'             => 'El costo no puede ser negativo.',
            'is_reminder_enabled.boolean' => 'El campo de recordatorio debe ser verdadero o falso.',
            'notes.string'         => 'Las notas deben ser una cadena de texto.',
        ];
    }
}
