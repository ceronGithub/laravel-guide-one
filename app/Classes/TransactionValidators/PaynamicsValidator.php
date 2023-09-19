<?php

namespace App\Classes\TransactionValidators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Contracts\TransactionValidator;

class PaynamicsValidator implements TransactionValidator {

    public function validate(Request $request) {
        $rules = [
            'client_id' => ['required'],
            'store_id' => ['required'],
            'machine_id' => ['required'],
            'transaction_no' => ['required'],
            'total_amount' => ['required'],
        ];

        $messages = [];

        return Validator::make($request->all(), $rules, $messages);
    }
}
