<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class Role extends ApiModel
{
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

    public function user()
    {
        return $this->hasOne(\App\Models\User::class);
    }
}

