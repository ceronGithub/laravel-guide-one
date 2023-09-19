<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class MachineSlot extends ApiModel
{
    const TBL_NAME = 'machine_slots';

    const COLUMN_PRODUCT_ID = 'product_id';
    const COLUMN_SLOT_ID = 'slot_id';
    const COLUMN_MACHINE_ID = 'machine_address_id';
    const COLUMN_MAX_COUNT = 'max_count';
    const COLUMN_CURRENT_COUNT = 'current_count';
    const COLUMN_RESERVE_QTY_COUNT = 'reserve_quantity_count';
    const COLUMN_SERIAL = 'serial';
    const COLUMN_STOCK_ALERT = 'stock_alert';

    const OBJECT_PRODUCT = 'product';
    const OBJECT_MACHINE = 'machine';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_PRODUCT_ID, self::COLUMN_SLOT_ID,
        self::COLUMN_MACHINE_ID, self::COLUMN_MAX_COUNT,
        self::COLUMN_CURRENT_COUNT, self::COLUMN_SERIAL,
        self::COLUMN_STOCK_ALERT, self::COLUMN_RESERVE_QTY_COUNT
    ];

    const TBL_COLUMN_NAMES = [
        "id",
        self::COLUMN_PRODUCT_ID, self::COLUMN_SLOT_ID,
        self::COLUMN_MACHINE_ID, self::COLUMN_MAX_COUNT,
        self::COLUMN_CURRENT_COUNT, self::COLUMN_SERIAL,
        self::COLUMN_STOCK_ALERT, self::COLUMN_RESERVE_QTY_COUNT
    ];

    public function isLowOnStocks(){
        return ($this->machine_slots_current_count <= ($this->machine_slots_max_count * 0.75));
    }

    public function getFirstItemSerial(){
        $array = array_map('trim', explode(',', $this->serial));

        $firstItem = isset($array[0]) ? $array[0] : null;

        return $firstItem;
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', self::COLUMN_PRODUCT_ID)->orderBy('price');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class, self::COLUMN_MACHINE_ID, Machine::COLUMN_MACHINE_ADDRESS_ID);
    }

    public function sortByPrice(bool $isAsc)
    {
        return $this->hasOne(Product::class, 'id', self::COLUMN_PRODUCT_ID)->orderBy('price');
    }

    public function isLowStocks()
    {
        return $this->machine_slots_current_count <= $this->machine_slots_max_count * 0.5;
    }


}
