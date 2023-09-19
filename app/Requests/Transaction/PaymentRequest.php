<?php

namespace App\Requests\Transaction;

use App\Models\MachineSlot;
use App\Models\PaymentDetail;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            PaymentDetail::COLUMN_TERMINAL_DATE => ['required',],
            PaymentDetail::COLUMN_TERMINAL_TIME => ['required',],
            PaymentDetail::COLUMN_TERMINAL_MESSAGE_STATUS => ['required',],
            PaymentDetail::COLUMN_CREATED_AT => ['date_format:Y-m-d H:i:s'],
            Transaction::COLUMN_PURCHASE_ORDER_ID => ['required',],
        ];
    }
}
