<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class Feature extends ApiModel
{
    const COLUMN_NAME = 'name';
    const COLUMN_DESC = 'desc';
    const COLUMN_ICON = 'icon';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME, self::COLUMN_DESC,
        self::COLUMN_ICON
    ];
}
