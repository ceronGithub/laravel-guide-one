<?php

namespace App\Models;

use App\Models\Base\ApiAuthenticatable;
use App\Models\UserStoreList;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends ApiAuthenticatable
{
    use HasApiTokens, HasRoles, Notifiable, LogsActivity;

    const COLUMN_USERNAME = 'username';
    const COLUMN_PASSWORD = 'password';
    const COLUMN_FIRST_NAME = 'first_name';
    const COLUMN_LAST_NAME = 'last_name';
    const COLUMN_EMAIL = 'email';
    const COLUMN_ACTIVE = 'active';
    const COLUMN_ROLE_ID = 'role_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_USERNAME, self::COLUMN_PASSWORD,
        self::COLUMN_FIRST_NAME, self::COLUMN_LAST_NAME,
        self::COLUMN_ACTIVE, self::COLUMN_ROLE_ID,
        self::COLUMN_EMAIL,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        self::COLUMN_PASSWORD
    ];

    protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function userstorelist()
    {
        return $this->belongsTo(UserStoreList::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class, UserStoreList::COLUMN_STORE_ID, 'id');
    }
}
