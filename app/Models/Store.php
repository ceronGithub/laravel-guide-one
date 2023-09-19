<?php

namespace App\Models;

use App\Models\Base\ApiModel;
use App\Models\UserStoreList;

class Store extends ApiModel
{
    const COLUMN_NAME = 'name';
    const COLUMN_DESC = 'desc';
    const COLUMN_STORE_CODE = 'store_code';
    //const COLUMN_USER_ID = 'user_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_DESC,
        self::COLUMN_STORE_CODE,
        //self::COLUMN_USER_ID
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /*
    protected $casts = [
        self::COLUMN_USER_ID => 'array'
    ];
    */
    public function userStore()
    {
        return $this->belongsTo(UserStoreList::class, 'id', UserStoreList::COLUMN_STORE_ID);
    }
}
