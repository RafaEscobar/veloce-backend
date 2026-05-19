<?php
/*
|--------------------------------------------------------------------------
| UpdateGasolineRefillRequest
|--------------------------------------------------------------------------
|
| Este archivo valida los datos al actualizar un registro existente
| de recarga de gasolina.
|
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGasolineRefillRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Por ahora permitimos a todos los usuarios autenticados
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
            'amount'      => ['sometimes', 'numeric', 'min:0'],
            'liters'      => ['sometimes', 'numeric', 'min:0'],
            'date'        => ['sometimes', 'nullable', 'date'],
            'gas_station' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas definidas.
     */
    public function messages(): array
    {
        return [
            'amount.numeric'       => 'El monto debe ser un número.',
            'amount.min'           => 'El monto no puede ser negativo.',
            'liters.numeric'       => 'Los litros deben ser un número.',
            'liters.min'           => 'Los litros no pueden ser negativos.',
            'date.date'            => 'La fecha no tiene un formato válido.',
            'gas_station.string'   => 'La gasolinera debe ser una cadena de texto.',
            'gas_station.max'      => 'La gasolinera no debe superar los 255 caracteres.',
        ];
    }
}
