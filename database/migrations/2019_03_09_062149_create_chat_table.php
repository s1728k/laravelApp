<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->unsignedInteger('cid');
            $table->string('message')->nullable();
            $table->unsignedInteger('fid')->nullable();
            $table->string('fap', 32)->nullable();
            $table->string('fname', 32)->nullable();
            $table->unsignedInteger('tid')->nullable();
            $table->string('tap', 32)->nullable();
            $table->string('tname', 32)->nullable();
            $table->string('style', 32)->nullable();
            $table->string('status', 20)->nullable();
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
        Schema::dropIfExists('chat');
    }
}
