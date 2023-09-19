<?php

namespace App\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class RegisterProductRequest extends FormRequest
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

        $rules = $this->validateInput();

        return $rules;
    }
    public function validateInput()
    {
        return [
            Product::COLUMN_NAME => ['required'],
            Product::COLUMN_DESC => ['required'],
            Product::COLUMN_FEATURE => ['required'],
            Product::COLUMN_SPECS => ['required'],
            Product::COLUMN_IMG => ['required'],
            Product::COLUMN_PRICE => ['required'],
            Product::COLUMN_CATEGORY_ID => ['required'],
            Product::COLUMN_CODE => ['required'],            
        ];
    }
}
