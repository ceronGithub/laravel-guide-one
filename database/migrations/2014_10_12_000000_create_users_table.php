<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string(User::COLUMN_USERNAME, 100)->unique();
            $table->string(User::COLUMN_PASSWORD, 100);
            $table->string(User::COLUMN_FIRST_NAME, 100);
            $table->string(User::COLUMN_LAST_NAME, 100);
            $table->string(User::COLUMN_EMAIL, 100)->unique();
            $table->boolean(User::COLUMN_ACTIVE)->default(true);
            $table->integer(User::COLUMN_ROLE_ID)->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
