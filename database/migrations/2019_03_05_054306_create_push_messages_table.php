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
            $table->text('body')->nullable();
            $table->text('icon')->nullable();
            $table->text('image')->nullable();
            $table->text('badge')->nullable();
            $table->text('vibrate')->nullable();
            $table->text('sound')->nullable();
            $table->string('dir')->nullable();
            $table->string('tag')->nullable();
            $table->string('data')->nullable();
            $table->boolean('requireInteraction')->default(true);
            $table->boolean('renotify')->default(true);
            $table->boolean('silent')->default(false);
            $table->text('actions')->nullable();
            $table->unsignedBigInteger('timestamp')->nullable();
            $table->string('lang')->nullable();
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
