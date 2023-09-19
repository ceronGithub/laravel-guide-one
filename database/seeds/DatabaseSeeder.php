<?php

use App\Models\Store;
use Database\Seeds\CategorySeeder;
use Database\Seeds\FeatureSeeder;
use Database\Seeds\ProductSeeder;
use Database\Seeds\RoleSeeder;
use Database\Seeds\StoreSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class, StoreSeeder::class,
            CategorySeeder::class, ProductSeeder::class,
            FeatureSeeder::class
        ]);
    }
}
