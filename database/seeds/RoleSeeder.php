<?php

namespace Database\Seeds;

use App\Models\Role;
use Database\Seeds\Base\BaseSeeder;

class RoleSeeder extends BaseSeeder
{

    protected function setTable(): string
    {
        return 'roles';
    }

    protected function setJsonPath(): string
    {
        return app_path() . '\jsons\seeds\roles.json';
    }

    protected function iteration($datum)
    {
        Role::create([
            Role::COLUMN_NAME => $datum[Role::COLUMN_NAME],
            Role::COLUMN_DESC => $datum[Role::COLUMN_DESC],
        ]);
    }
}
