<?php

use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string(Transaction::COLUMN_PURCHASE_ORDER_ID, 100)->unique();
            $table->string(Transaction::COLUMN_PRODUCT_NAME);
            $table->double(Transaction::COLUMN_AMOUNT, 12, 4)->default(0.0);
            $table->string(Transaction::COLUMN_MACHINE_ADDRESS_ID);
            $table->string(Transaction::COLUMN_MACHINE_SLOT_ID);
            $table->string(Transaction::COLUMN_TRANSACTION_ID, 100)->unique()->nullable();
            $table->integer(Transaction::COLUMN_TRANSACTION_TYPE);
            $table->string(Transaction::COLUMN_TRANSACTION_DESCRIPTION, 100);
            $table->string(Transaction::COLUMN_PAYMENT_DETAILS_ID)->nullable();
            $table->dateTime(Transaction::COLUMN_REQUEST_DATETIME_EXPIRY);

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
        Schema::dropIfExists('transactions');
    }
}
