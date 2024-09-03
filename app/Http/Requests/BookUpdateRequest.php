<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust this if authorization logic is needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'requiered|string|max:50',
            'author' => 'required|string|max:50 min:3',
            'description' => 'required|string|max:1000',
            'published_at' => 'required|integer|min:1995|max:2023',
          
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'author.required' => 'The author is required.',
            'description.required' => 'The description is required.',
            'published_at.required' => 'The published_at is required.',
            'title.string' => 'The title must be a string.',
            'author.string' => 'The author must be a string.',
            'description.string' => 'The description must be a string.',
            
        ];
    }
}
