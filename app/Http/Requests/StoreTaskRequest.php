<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title' => 'required|string|max:50|unique:tasks,title',
            'description' => 'required|string',
            'status' => 'required|in:pendiente,en proceso,completada',
            'priority' => 'required|in:baja,media,alta',
            //anio-mes-dia
            'due_date' => 'nullable|date|after_or_equal:today',
            'user_id' => 'required|exists:users,id'
        ];
    }

}
