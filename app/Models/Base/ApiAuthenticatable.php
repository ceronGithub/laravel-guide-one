<?php

namespace App\Models\Base;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DateTimeInterface;

abstract class ApiAuthenticatable extends Authenticatable
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
