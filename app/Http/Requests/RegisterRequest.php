<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => ['max:20'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['required','between:8,24','regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,24}$/'],
            'password_confirmation' => ['required','same:password']
        ];
    }
}
