<?php

namespace App\Models;

use App\Models\Base\ApiModel;
use Carbon\Carbon;

class Machine extends ApiModel
{
    const TBL_NAME = 'machines';

    const COLUMN_NAME = 'name';
    const COLUMN_DESC = 'desc';
    const COLUMN_MACHINE_ADDRESS_ID = 'machine_address_id';
    const COLUMN_STORE_ID = 'store_id';
    const COLUMN_LAST_CONNECTED = 'last_connected';
    const COLUMN_PRINTER_STATUS= 'printer_status';
    const COLUMN_PIN= 'pin';

    const OBJECT_STORE = 'store';
    const OBJECT_TRANSACTIONS = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME, self::COLUMN_DESC,
        self::COLUMN_MACHINE_ADDRESS_ID, self::COLUMN_STORE_ID,
        self::COLUMN_LAST_CONNECTED, self::COLUMN_PRINTER_STATUS,
        self::COLUMN_PIN
    ];

    const TBL_COLUMN_NAMES = [
        "id",
        self::COLUMN_NAME, self::COLUMN_DESC,
        self::COLUMN_MACHINE_ADDRESS_ID, self::COLUMN_STORE_ID,
        self::COLUMN_LAST_CONNECTED, self::COLUMN_PRINTER_STATUS,
        self::COLUMN_PIN
    ];

    public function isOnline(){
        if($this->last_connected == null)
            return false;

        $time = Carbon::parse($this->last_connected);
        $endTime = $time->addMinutes(2);

        return ($endTime > Carbon::now());

    }

    public function store()
    {
        return $this->belongsTo(Store::class, self::COLUMN_STORE_ID, 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, Transaction::COLUMN_MACHINE_ADDRESS_ID, self::COLUMN_MACHINE_ADDRESS_ID);
    }

    /**
     * Returns MachineSlot
     *
     */
    public function machineSlot()
    {
        return $this->hasMany('App\Models\MachineSlot', MachineSlot::COLUMN_MACHINE_ID, 'id');
    }
}
