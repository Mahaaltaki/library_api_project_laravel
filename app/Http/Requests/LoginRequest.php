<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation;
use Illuminate\Contracts\Support\MessageProvider;

class LoginRequest extends FormRequest
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
            'name' =>'required|string|max:20',
            'email' => 'required|string|max:30|email',
            'password' => 'required|min:8|max:30',
        ];
    }
    /*
    @return array<string,string>
    */
    public function messages(): array
    {
        return [
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.exists' => 'The provided email does not exist in our records.',
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a string.',
        ];
    }
}
