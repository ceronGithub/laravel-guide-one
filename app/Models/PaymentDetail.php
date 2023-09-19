<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class PaymentDetail extends ApiModel
{
    const TBL_NAME = 'payment_details';

    const COLUMN_PAYMENT_ID = 'payment_id';
    const COLUMN_TERMINAL_MESSAGE_STATUS = 'terminal_message_status';
    const COLUMN_TERMINAL_MERCHANT = 'terminal_merchant';
    const COLUMN_TERMINAL_DATE = 'terminal_date';
    const COLUMN_TERMINAL_TIME = 'terminal_time';
    const COLUMN_TERMINAL_PAID_AMOUNT = 'terminal_paid_amount';
    const COLUMN_TERMINAL_APPR_CODE = 'terminal_appr_code';
    const COLUMN_TERMINAL_TRACE_NO = 'terminal_trace_no';
    const COLUMN_TERMINAL_TID = 'terminal_tid';
    const COLUMN_TERMINAL_MID = 'terminal_mid';
    const COLUMN_TERMINAL_PAYMENT_MODE = 'terminal_payment_mode';
    const COLUMN_TERMINAL_PAYMENT_MODE_VALUE = 'terminal_payment_mode_value';
    const COLUMN_TERMINAL_PAYMENT_MODE_DATE = 'terminal_payment_mode_date';
    const COLUMN_TERMINAL_BATCH_NUM = 'terminal_batch_num';
    const COLUMN_TERMINAL_REF_NUM = 'terminal_ref_num';
    const COLUMN_CREATED_AT = 'created_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_PAYMENT_ID,
        self::COLUMN_TERMINAL_MESSAGE_STATUS, self::COLUMN_TERMINAL_MERCHANT,
        self::COLUMN_TERMINAL_DATE, self::COLUMN_TERMINAL_TIME,
        self::COLUMN_TERMINAL_PAID_AMOUNT, self::COLUMN_TERMINAL_APPR_CODE,
        self::COLUMN_TERMINAL_TRACE_NO, self::COLUMN_TERMINAL_TID,
        self::COLUMN_TERMINAL_MID, self::COLUMN_TERMINAL_PAYMENT_MODE,
        self::COLUMN_TERMINAL_PAYMENT_MODE_VALUE, self::COLUMN_TERMINAL_PAYMENT_MODE_DATE,
        self::COLUMN_TERMINAL_BATCH_NUM, self::COLUMN_TERMINAL_REF_NUM,
        self::COLUMN_CREATED_AT
    ];

    const TBL_COLUMN_NAMES = [
        "id",
        self::COLUMN_PAYMENT_ID,
        self::COLUMN_TERMINAL_MESSAGE_STATUS, self::COLUMN_TERMINAL_MERCHANT,
        self::COLUMN_TERMINAL_DATE, self::COLUMN_TERMINAL_TIME,
        self::COLUMN_TERMINAL_PAID_AMOUNT, self::COLUMN_TERMINAL_APPR_CODE,
        self::COLUMN_TERMINAL_TRACE_NO, self::COLUMN_TERMINAL_TID,
        self::COLUMN_TERMINAL_MID, self::COLUMN_TERMINAL_PAYMENT_MODE,
        self::COLUMN_TERMINAL_PAYMENT_MODE_VALUE, self::COLUMN_TERMINAL_PAYMENT_MODE_DATE,
        self::COLUMN_TERMINAL_BATCH_NUM, self::COLUMN_TERMINAL_REF_NUM,
        self::COLUMN_CREATED_AT,"updated_at"
    ];
}
