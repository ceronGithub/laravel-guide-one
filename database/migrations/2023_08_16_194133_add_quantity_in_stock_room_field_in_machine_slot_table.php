<?php

use App\Models\MachineSlot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityInStockRoomFieldInMachineSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('machine_slots', function (Blueprint $table) {
            $table->integer(MachineSlot::COLUMN_RESERVE_QTY_COUNT)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_room_field_in_machine_slot', function (Blueprint $table) {
            //
        });
    }
}
