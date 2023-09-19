<?php

namespace App\Requests\Machine;

use App\Models\MachineSlot;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMachineSlotRequest extends FormRequest
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
            'current-count' => ['required', 'numeric'],
            'max-count' => ['required', 'numeric'],
            'product-id' => ['required'],
            'index' => ['required'],
            'spare-quantity' => ['nullable', 'numeric'],
            MachineSlot::COLUMN_STOCK_ALERT => ['required','numeric'],
            MachineSlot::COLUMN_SERIAL => ['nullable'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator)->withInput();
    }
}
