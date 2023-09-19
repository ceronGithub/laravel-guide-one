<?php

namespace Database\Seeds;

use App\Models\Feature;
use Database\Seeds\Base\BaseSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends BaseSeeder
{

    protected function setTable(): string
    {
        return 'features';
    }

    protected function setJsonPath(): string
    {
        return app_path() . '\jsons\seeds\feature.json';
    }

    protected function iteration($datum)
    {
        Feature::create([
            Feature::COLUMN_NAME => $datum[Feature::COLUMN_NAME],
            Feature::COLUMN_DESC => $datum[Feature::COLUMN_DESC],
            Feature::COLUMN_ICON => $datum[Feature::COLUMN_ICON],
        ]);
    }
}
