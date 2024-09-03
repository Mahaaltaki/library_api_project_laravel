<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RatingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust this if authorization logic is required
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'sometimes|integer|between:1,5',
            // Ensure the book exists
            'Book_id' => [
                'required',
                'integer',
                Rule::exists('books', 'id'),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set the movie_id from the route
        $this->merge([
            'book_id' => $this->route('bookId'),
            'user_id' => auth()->id(),
        ]);
    }
}
