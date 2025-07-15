<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255|min:3',
            'description' => 'required|max:255|min:3',
            'completed' => 'required|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Task name is required.',
            'name.min' => 'Task name must be at least 3 characters.',
            'name.max' => 'Task name must be less than 255 characters.',
            'description.required' => 'Task description is required.',
            'description.min' => 'Task description must be at least 3 characters.',
            'description.max' => 'Task description must be less than 255 characters.',
        ];
    }
}
