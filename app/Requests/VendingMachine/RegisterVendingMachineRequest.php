<?php

namespace App\Requests\VendingMachine;

use App\Models\Machine;
use Illuminate\Foundation\Http\FormRequest;

class RegisterVendingMachineRequest extends FormRequest
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
            Machine::COLUMN_NAME => ['required'],            
            Machine::COLUMN_DESC => ['required',],
            Machine::COLUMN_MACHINE_ADDRESS_ID => ['required'],
            Machine::COLUMN_STORE_ID => ['required'],
        ];
    }
}
