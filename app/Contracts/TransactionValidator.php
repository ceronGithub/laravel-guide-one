<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

interface TransactionValidator {

    function validate(Request $request);
}
