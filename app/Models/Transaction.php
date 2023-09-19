<?php

namespace App\Models;

use App\Models\Base\ApiModel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class Transaction extends ApiModel
{
    const TBL_NAME = 'transactions';

    const COLUMN_PURCHASE_ORDER_ID       = 'purchase_order_id';
    const COLUMN_PRODUCT_NAME            = 'product_name';
    const COLUMN_AMOUNT                  = 'product_price';
    const COLUMN_MACHINE_ADDRESS_ID      = 'machine_address_id';
    const COLUMN_MACHINE_SLOT_ID         = 'machine_slot_address_id';
    const COLUMN_REQUEST_DATETIME_EXPIRY = 'request_datetime_expiry';
    const COLUMN_TRANSACTION_ID          = 'transaction_id';
    const COLUMN_TRANSACTION_TYPE        = 'transaction_type';
    const COLUMN_TRANSACTION_DESCRIPTION = 'transaction_description';
    const COLUMN_PAYMENT_DETAILS_ID      = 'payment_details_id';
    const COLUMN_PRODUCT_SERIAL          = 'product_serial';
    const COLUMN_STORE_CODE              = 'store_code';
    const COLUMN_PRODUCT_CODE            = 'product_code';
    const COLUMN_PRODUCT_ID              = 'product_id';

    const OBJECT_PAYMENT_DETAILS         = 'payment_details';
    const OBJECT_MACHINE                 = 'machine';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_TRANSACTION_ID, self::COLUMN_PRODUCT_NAME,
        self::COLUMN_MACHINE_ADDRESS_ID, self::COLUMN_MACHINE_SLOT_ID,
        self::COLUMN_REQUEST_DATETIME_EXPIRY, self::COLUMN_TRANSACTION_TYPE,
        self::COLUMN_AMOUNT, self::COLUMN_TRANSACTION_DESCRIPTION,
        self::COLUMN_PURCHASE_ORDER_ID, self::COLUMN_PAYMENT_DETAILS_ID,
        self::COLUMN_PAYMENT_DETAILS_ID, self::COLUMN_PRODUCT_SERIAL,
        self::COLUMN_STORE_CODE, self::COLUMN_PRODUCT_CODE,
        self::COLUMN_PRODUCT_ID
    ];

    const TBL_COLUMN_NAMES = [
        "id",self::COLUMN_PURCHASE_ORDER_ID, self::COLUMN_PAYMENT_DETAILS_ID,
        self::COLUMN_TRANSACTION_ID, self::COLUMN_PRODUCT_NAME,
        self::COLUMN_AMOUNT, self::COLUMN_MACHINE_ADDRESS_ID,
        self::COLUMN_MACHINE_SLOT_ID, self::COLUMN_TRANSACTION_TYPE,
        self::COLUMN_TRANSACTION_DESCRIPTION, self::COLUMN_REQUEST_DATETIME_EXPIRY,
        "created_at", "updated_at",self::COLUMN_PAYMENT_DETAILS_ID,
        self::COLUMN_PRODUCT_SERIAL
    ];

    public function isOrderExpired(): Bool{
        return ($this->request_datetime_expiry < Carbon::now() && $this->transaction_type == 1);
    }

    public function payment_details()
    {
        return $this->hasOne(PaymentDetail::class, PaymentDetail::COLUMN_PAYMENT_ID, self::COLUMN_PAYMENT_DETAILS_ID);
    }

    public function payment_responses()
    {
        return $this->hasOne(Responses::class, 'transaction_id');
    }

    public function machine()
    {
        return $this->hasOne(Machine::class, Machine::COLUMN_MACHINE_ADDRESS_ID, self::COLUMN_MACHINE_ADDRESS_ID);
    }

    protected function setMachineSlotAddressIdAttribute($value)
    {
        $this->attributes[self::COLUMN_MACHINE_SLOT_ID] = substr("0{$value}", -2);
    }
}
