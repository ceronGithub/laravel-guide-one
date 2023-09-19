<?php

namespace App\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];

        $rules = $this->validateUser();

        return $rules;
    }

    public function validateUser()
    {
        return [
            'first_name' => [
                'required', 'min:2', 'max:100',
            ],
            'last_name' => [
                'required', 'min:2', 'max:100',
            ],
            'username' => [
                'required', 'min:5', 'max:100',
            ],
            'email' => 'required|email:rfc,dns|max:100|unique:users,email',
            'password' => 'required|min:14|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]+$/',
        ];
    }
}
