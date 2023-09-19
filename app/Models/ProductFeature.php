<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class ProductFeature extends ApiModel
{
    const COLUMN_PRODUCT_ID = 'product_id';
    const COLUMN_FEATURE_ID = 'feature_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_PRODUCT_ID, self::COLUMN_FEATURE_ID
    ];
}
