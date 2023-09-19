<?php

namespace Database\Seeds;

use App\Models\Category;
use Database\Seeds\Base\BaseSeeder;

class CategorySeeder extends BaseSeeder
{

    protected function setTable(): string
    {
        return 'categories';
    }

    protected function setJsonPath(): string
    {
        return app_path() . '\jsons\seeds\categories.json';
    }

    protected function iteration($datum)
    {
        Category::create([
            Category::COLUMN_NAME => $datum[Category::COLUMN_NAME],
            Category::COLUMN_DESC => $datum[Category::COLUMN_DESC],
        ]);
    }
}
