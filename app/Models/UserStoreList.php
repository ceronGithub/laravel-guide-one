<?php

namespace App\Models;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserStoreList extends Model
{
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_STORE_ID = 'store_id';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_USER_ID, self::COLUMN_STORE_ID
    ];

    public function user()
    {
        return $this->belongsTo(User::class, self::COLUMN_USER_ID, 'id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', self::COLUMN_STORE_ID);
    }
}
