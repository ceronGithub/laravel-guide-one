<?php

namespace App\Classes\Constants;

class PaynamicsResponseCodes{
    const RESPONSE_CODE_KEY = 'response_code';
    const PENDING     = 'GR033';
    const SUCCESS     = 'GR001';
    const SUCCESS_3DS = 'GR002';
    const EXPIRED     = 'GR152';

    //Exception codes
    const API_RESPONSE_CODES = [
        'PN001' => 'Success',
        'PN002' => 'Pending',
        'PN003' => 'Failed',
        'PN004' => 'Error in parsing response from Paynamics',
        'PN005' => 'Missing payment URL from response',
        'PN006' => 'Failed to obtain a payment URL',
        'PN007' => 'Failed to process the request',
        'PN008' => 'Signature verification failed',
        'PN009' => 'Failed to save response',
        'PN010' => 'Failed to save request',
    ];

    const SUCCESS_RESPONSE             = 'PN001';
    const PENDING_RESPONSE             = 'PN002';
    const ERROR_PARSE_RESPONSE         = 'PN004';
    const ERROR_MISSING_PAY_URL        = 'PN005';
    const ERROR_FAILED_TO_OBTAIN_URL   = 'PN006';
    const ERROR_PROCESSING_REQUEST     = 'PN007';
    const ERROR_SIGNATURE_VERIFICATION = 'PN008';
    const ERROR_SAVING_RESPONSE        = 'PN009';
    const ERROR_SAVING_REQUEST         = 'PN010';
}
