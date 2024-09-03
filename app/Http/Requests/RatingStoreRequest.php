<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RatingStoreRequest extends FormRequest
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
            'rating' => 'required|integer|max:5|min1',
            'book_id' => [
                'required',
                'integer',
                Rule::exists('bookes', 'id'),
            ],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('id', auth()->id());
                }),
            ],
        ];
    }

    /**
     * Modify the data before validation runs.
     */
    protected function prepareForValidation()
    {
        // Merge route parameters into request data before validation
        $this->merge([
            'movie_id' => $this->route('movieId'),
            'user_id' => auth()->id(),
        ]);
    }
}
