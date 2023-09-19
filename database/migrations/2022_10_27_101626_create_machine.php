<?php

use App\Models\Machine;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string(Machine::COLUMN_NAME, 100);
            $table->text(Machine::COLUMN_DESC);
            $table->string(Machine::COLUMN_MACHINE_ADDRESS_ID, 100)->unique();
            $table->unsignedInteger(Machine::COLUMN_STORE_ID);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machines');
    }
}
