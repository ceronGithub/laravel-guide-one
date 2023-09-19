<?php

namespace Database\Seeds;

use App\Models\Product;
use Database\Seeds\Base\BaseSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends BaseSeeder
{
    protected function setTable(): string
    {
        return 'products';
    }

    protected function setJsonPath(): string
    {
        return app_path() . '\jsons\seeds\products.json';
    }

    protected function iteration($datum)
    {
        Product::create([
            Product::COLUMN_CODE        => $datum[Product::COLUMN_CODE],
            Product::COLUMN_NAME        => $datum[Product::COLUMN_NAME],
            Product::COLUMN_DESC        => $datum[Product::COLUMN_DESC],
            Product::COLUMN_IMG         => json_encode($datum[Product::COLUMN_IMG]),
            Product::COLUMN_CATEGORY_ID => $datum[Product::COLUMN_CATEGORY_ID],
            Product::COLUMN_PRICE       => $datum[Product::COLUMN_PRICE],
            Product::COLUMN_FEATURE     => $datum[Product::COLUMN_FEATURE],
            Product::COLUMN_SPECS       => $datum[Product::COLUMN_SPECS],
        ]);
    }
}
