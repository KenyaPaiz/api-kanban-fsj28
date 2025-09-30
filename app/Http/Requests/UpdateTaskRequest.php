<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'due_date' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El titulo es obligatorio',
            'title.string' => 'El titulo debe ser una cadena de texto',
            'title.max' => 'El titulo no debe exceder los 50 caracteres',
            'description.required' => 'La descripcion es obligatoria',
            'description.string' => 'La descripcion debe ser una cadena de texto',
            'due_date.date' => 'La fecha debe tener un formato de fecha valido',
            'due_date.after_or_equal' => 'La fecha debe ser igual o posterior a la fecha actual'
        ];
    }
}
