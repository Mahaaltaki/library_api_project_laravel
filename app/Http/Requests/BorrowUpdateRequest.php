<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowUpdateRequest extends FormRequest
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
                'book_id'=> 'required|integer',
            'user_id' => 'Auth::$user->id',
                'borrowed_at' => 'required|date',
                'due_date' => 'required|date|after_or_equal:borrowed_at|before_or_equal:' . now()->addDays(14)->format('Y-m-d'),
                'returned_at' => 'required|date|after_or_equal:borrowed_at|before_or_equal:' . $this->borrowed_at_plus_14(),
            ];
        }
        /**
         * Calculate the due date (borrowed_at + 14 days).
         */
        protected function borrowed_at_plus_14()
        {
            return $this->borrowed_at ? date('Y-m-d', strtotime($this->borrowed_at . ' +14 days')) : now()->addDays(14)->format('Y-m-d');
        }
        
        /**
         * Custom messages for validation errors.
         */
        public function messages(): array
        {
            return [
                'returned_at.before_or_equal' => 'لقد تأخرت في إعادة الكتاب. يرجى ملاحظة أن آخر تاريخ لإعادة الكتاب هو :date.',
                'returned_at.after_or_equal' => 'تاريخ الإرجاع يجب أن يكون بعد أو في نفس يوم الاستعارة.',
                'due_date.before_or_equal' => 'تاريخ الإعادة يجب أن يكون خلال 14 يوماً من تاريخ الاستعارة.',
                'due_date.after_or_equal' => 'تاريخ الإعادة يجب أن يكون بعد أو في نفس يوم الاستعارة.',
            ];
        }
         /**
         * Custom field attributes for validation errors.
         */
        public function attributes()
        {
            return [
                'borrowed_at' => 'تاريخ الاستعارة',
                'due_date' => 'تاريخ الإعادة',
                'returned_at' => 'تاريخ الإرجاع',
            ];
        }
        /**
         * Handle a failed validation attempt.
         *
         * @param  \Illuminate\Contracts\Validation\Validator  $validator
         * @return void
         *
         * @throws \Illuminate\Validation\ValidationException
         */
        protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
        {
            $errors = $validator->errors();
            $response = response()->json([
                'message' => 'لم يتم تحقيق شروط التحقق. تأكد من أن تواريخ الإرجاع صحيحة.',
                'errors' => $errors->all(),
            ], 422);
    
            throw new \Illuminate\Validation\ValidationException($validator, $response);
        }
    
        /**
         * Handle a successful validation attempt.
         *
         * @return void
         */
        protected function passedValidation()
        {
            $response = response()->json([
                'message' => 'شكراً لإعادة الكتاب في الوقت المحدد!',
            ], 200);
    
            response()->json($response);
        }
    
    }
    