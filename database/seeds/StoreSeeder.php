<?php

namespace Database\Seeds;

use App\Models\Store;
use Database\Seeds\Base\BaseSeeder;
use Illuminate\Database\Seeder;

class StoreSeeder extends BaseSeeder
{

    protected function setTable(): string
    {
        return 'stores';
    }

    protected function setJsonPath(): string
    {
        return app_path() . '\jsons\seeds\stores.json';
    }

    protected function iteration($datum)
    {
        Store::create([
            Store::COLUMN_NAME      => $datum[Store::COLUMN_NAME],
            Store::COLUMN_DESC      => $datum[Store::COLUMN_DESC],
            //Store::COLUMN_USER_ID   => $datum[Store::COLUMN_USER_ID],
        ]);
    }
}
