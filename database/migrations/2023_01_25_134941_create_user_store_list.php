<?php

use App\Models\UserStoreList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStoreList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_store_lists', function (Blueprint $table) {
            $table->id();
            $table->integer(UserStoreList::COLUMN_USER_ID);
            $table->integer(UserStoreList::COLUMN_STORE_ID);
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
        Schema::dropIfExists('user_store_lists');
    }
}
