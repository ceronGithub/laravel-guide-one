<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class Product extends ApiModel
{
    const TBL_NAME = 'products';

    const COLUMN_NAME = 'name';
    const COLUMN_DESC = 'desc';
    const COLUMN_FEATURE = 'feature';
    const COLUMN_SPECS = 'specification';
    const COLUMN_IMG = 'image';
    const COLUMN_PRICE = 'price';
    const COLUMN_CATEGORY_ID = 'category_id';
    const COLUMN_CODE = 'product_code';

    const SAVE_SCREENSHOT_PATH = 'storage/uploads/product/';
    const SAVE_SCREENSHOT_PATH_ESCAPED = "/(storage/uploads/share_screenshots/)/";
    const SAVE_SCREENSHOT_EXT = '.jpg';

    const OBJECT_MACHINE_SLOT = 'machine_slot';
    const OBJECT_CATEGORY = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME, self::COLUMN_DESC,
        self::COLUMN_IMG, self::COLUMN_PRICE,
        self::COLUMN_CATEGORY_ID, self::COLUMN_FEATURE,
        self::COLUMN_SPECS, self::COLUMN_CODE
    ];

    const TBL_COLUMN_NAMES = [
        "id",
        self::COLUMN_NAME, self::COLUMN_DESC,
        self::COLUMN_IMG, self::COLUMN_PRICE,
        self::COLUMN_CATEGORY_ID, self::COLUMN_FEATURE,
        self::COLUMN_SPECS, self::COLUMN_CODE
    ];

    public function machine_slot()
    {
        return $this->belongsTo('App\Models\MachineSlot', 'id', MachineSlot::COLUMN_PRODUCT_ID);
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', self::COLUMN_CATEGORY_ID);
    }
    /**
     *
     */
    public function getImageAttribute($value)
    {
        return json_decode($value);
    }
}
