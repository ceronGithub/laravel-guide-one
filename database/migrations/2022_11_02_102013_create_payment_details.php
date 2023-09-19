<?php

use App\Models\PaymentDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string(PaymentDetail::COLUMN_PAYMENT_ID, 100);
            $table->string(PaymentDetail::COLUMN_TERMINAL_MESSAGE_STATUS, 100);
            $table->string(PaymentDetail::COLUMN_TERMINAL_MERCHANT, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_DATE, 100);
            $table->string(PaymentDetail::COLUMN_TERMINAL_TIME, 100);
            $table->string(PaymentDetail::COLUMN_TERMINAL_PAID_AMOUNT, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_APPR_CODE, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_TRACE_NO, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_TID, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_MID, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_PAYMENT_MODE, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_PAYMENT_MODE_VALUE, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_PAYMENT_MODE_DATE, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_BATCH_NUM, 100)->nullable();
            $table->string(PaymentDetail::COLUMN_TERMINAL_REF_NUM, 100)->nullable();
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
        Schema::dropIfExists('payment_details');
    }
}
