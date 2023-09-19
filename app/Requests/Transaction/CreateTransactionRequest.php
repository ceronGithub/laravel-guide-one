<?php

namespace App\Requests\Transaction;

use App\Models\MachineSlot;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
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
            MachineSlot::COLUMN_PRODUCT_ID => ['required',],
            Transaction::COLUMN_MACHINE_ADDRESS_ID => ['required',],
            Transaction::COLUMN_MACHINE_SLOT_ID => ['required',],
        ];
    }
}
