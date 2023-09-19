<?php

namespace App\Requests\Machine;

use App\Models\Machine;
use Illuminate\Foundation\Http\FormRequest;

class CheckMachineRegisteredRequest extends FormRequest
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
            Machine::COLUMN_MACHINE_ADDRESS_ID => ['required',],
        ];
    }
}
