<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Transaction;
class AddSerialNoOnTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string(Transaction::COLUMN_PRODUCT_CODE)->nullable(true)->after(TRANSACTION::COLUMN_MACHINE_ADDRESS_ID);
            $table->string(Transaction::COLUMN_STORE_CODE)->nullable(true)->after(TRANSACTION::COLUMN_MACHINE_ADDRESS_ID);
            $table->string(Transaction::COLUMN_PRODUCT_ID)->nullable(true)->after(TRANSACTION::COLUMN_MACHINE_ADDRESS_ID);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
