<?php

namespace App\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterUserManagementRequest extends FormRequest
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
    public function rules()
    {
        $rules = $this->validateInput();

        return $rules;
    }
    public function validateInput()
    {
        return [
            User::COLUMN_FIRST_NAME => ['required'],
            User::COLUMN_LAST_NAME => ['required'],
            User::COLUMN_USERNAME => ['required'],
            User::COLUMN_ROLE_ID => ['required'],
            User::COLUMN_ACTIVE => ['required'],
            User::COLUMN_EMAIL => ['required', 'email:rfc,dns', 'max:100', 'unique:users,email'],
            User::COLUMN_PASSWORD => ['required', 'min:14', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]+$/'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function getValues()
    {
        return [
            User::COLUMN_FIRST_NAME => $this->first_name,
            User::COLUMN_LAST_NAME => $this->last_name,
            User::COLUMN_USERNAME => $this->username,
            User::COLUMN_ROLE_ID => $this->role_id,
            User::COLUMN_EMAIL => $this->email,
            User::COLUMN_PASSWORD => Hash::make($this->password),
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 14 characters long.',
            'password.regex' => 'The password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.',
        ];
    }
}
