<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->string('title')->nullable();
            $table->string('actions')->nullable();
            $table->string('badge')->nullable();
            $table->string('body')->nullable();
            $table->string('dir')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('lang')->nullable();
            $table->string('renotify')->default(true);
            $table->string('requireInteraction')->default(true);
            $table->string('tag')->nullable();
            $table->string('vibrate')->nullable();
            $table->string('data')->nullable();
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
        Schema::dropIfExists('push_messages');
    }
}
