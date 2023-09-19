<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string(Product::COLUMN_NAME, 100);
            $table->text(Product::COLUMN_DESC);
            $table->text(Product::COLUMN_IMG);
            $table->double(Product::COLUMN_PRICE, 12, 4)->default(0.0);
            $table->unsignedInteger(Product::COLUMN_CATEGORY_ID);
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
        Schema::dropIfExists('products');
    }
}
