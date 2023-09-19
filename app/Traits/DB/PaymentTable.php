<?php

namespace App\Traits\DB;

use App\Models\PaymentDetail;
use App\Models\Product;

trait PaymentTable
{

    public function insertPaymentData(array $array)
    {
        $data = PaymentDetail::create($array);
        return $data;
    }

    public function addPaymentId(array $array): array
    {
        $array = array_merge($array, [
            PaymentDetail::COLUMN_PAYMENT_ID => uniqid('P'),
        ]);
        return $array;
    }

}
