<?php

namespace App\Requests\Category;

use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterCategoryRequest extends FormRequest
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
        /*
        return [
            //
        ];
        */
        $rules = [];

        $rules = $this->validateInput();

        return $rules;
    }

    public function validateInput()
    {
        return [
            Category::COLUMN_NAME => ['required'],
            Category::COLUMN_DESC => ['required'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator)->withInput();
    }

}
