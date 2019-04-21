<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('_token',60);
            $table->unsignedInteger('expiry');
            $table->unsignedInteger('app_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('auth_provider')->nullable();
            $table->string('user_name')->nullable();
            $table->unsignedInteger('chat_resource_id')->default(0);
            $table->text('user_agent')->nullable();
            $table->string('ip_address',45)->nullable();
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
        Schema::dropIfExists('sessions');
    }
}
