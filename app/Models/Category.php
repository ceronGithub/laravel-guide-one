<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class Category extends ApiModel
{
    const TBL_NAME = 'categories';

    const COLUMN_NAME = 'name';
    const COLUMN_DESC = 'desc';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME, self::COLUMN_DESC
    ];

    const TBL_COLUMN_NAMES = [
        "id",
        self::COLUMN_NAME, self::COLUMN_DESC
    ];

    public function category_id()
    {
        return $this->belongsTo(Product::class, 'id', Product::COLUMN_CATEGORY_ID);
    }
}
