<?php
namespace App\Traits\DB;

use App\Models\Role;

    trait RoleTable
    {
        public function getRole()
        {
            return Role::all();
        }
    }
?>