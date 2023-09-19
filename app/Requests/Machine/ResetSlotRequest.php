<?php

namespace App\Requests\Machine;

use App\Models\Machine;
use App\Models\MachineSlot;
use Illuminate\Foundation\Http\FormRequest;

class ResetSlotRequest extends FormRequest
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
            MachineSlot::COLUMN_SLOT_ID => ['required',],
            MachineSlot::COLUMN_MACHINE_ID => ['required'],
        ];
    }
}
