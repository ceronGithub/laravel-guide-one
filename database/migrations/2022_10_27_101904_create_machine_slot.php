<?php

use App\Models\MachineSlot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachineSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->string(MachineSlot::COLUMN_MACHINE_ID);
            $table->string(MachineSlot::COLUMN_SLOT_ID, 3);
            $table->unsignedInteger(MachineSlot::COLUMN_PRODUCT_ID);
            $table->integer(MachineSlot::COLUMN_MAX_COUNT);
            $table->integer(MachineSlot::COLUMN_CURRENT_COUNT);
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
        Schema::dropIfExists('machine_slots');
    }
}
