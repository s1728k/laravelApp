<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('avatar')->nullable();
            $table->integer('active_app_id')->default(0);
            $table->string('hidden_modules')->default('["Licenses"]');
            $table->string('online_status')->default('offline');
            $table->unsignedInteger('chat_resource_id')->default(0);
            $table->text('chat_friends')->nullable();
            $table->string('email_varification');
            $table->boolean('blocked')->default(false);
            $table->float('recharge_balance');
            $table->date('recharge_expiry_date');
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
