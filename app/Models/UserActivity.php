<?php

namespace App\Models;

use App\Models\Base\ApiModel;

class UserActivity extends ApiModel
{
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_DESC = 'description';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_USER_ID, self::COLUMN_DESC
    ];
}
