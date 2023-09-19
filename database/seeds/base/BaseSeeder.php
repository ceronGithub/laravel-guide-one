<?php

namespace Database\Seeds\Base;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->setTable())->truncate();

        $json_object = file_get_contents($this->setJsonPath());

        $data = json_decode($json_object, true);

        foreach ($data as $datum) {
            $this->iteration($datum);
        }
    }

    abstract protected function setTable(): string;
    abstract protected function setJsonPath(): string;
    abstract protected function iteration($datum);
}
