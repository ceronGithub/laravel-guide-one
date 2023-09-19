<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessorTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('processor_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('store_id');
            $table->string('machine_id');
            $table->string('request_id', 255);
            $table->string('transaction_no', 100);
            $table->string('payment_method', 100);
            $table->json('customer_info');
            $table->json('transaction_info');
            $table->json('billing_info');
            $table->json('order_details');
            $table->json('transaction_payload');
            $table->longText('signature');
            $table->string('response_code', 20);
            $table->string('response_message', 255);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}
