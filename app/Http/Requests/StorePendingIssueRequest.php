<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendingIssueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'issue_status_id' => ['required', 'exists:issue_statuses,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'priority_id' => ['required', 'exists:issue_priorities,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'El campo vehículo es obligatorio.',
            'vehicle_id.exists' => 'El vehículo seleccionado no existe.',
            'issue_status_id.required' => 'El campo estado del problema es obligatorio.',
            'issue_status_id.exists' => 'El estado del problema seleccionado no existe.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no debe superar los 255 caracteres.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'date.required' => 'El campo fecha es obligatorio.',
            'date.date' => 'El campo fecha debe ser una fecha válida.',
            'priority_id.required' => 'El campo prioridad es obligatorio.',
            'priority_id.exists' => 'La prioridad seleccionada no existe.',
        ];
    }
}
