<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aid')->nullable();
            $table->unsignedInteger('fid')->nullable();
            $table->string('fap')->nullable();
            $table->unsignedInteger('qid')->nullable();
            $table->string('query_nick_name')->nullable();
            $table->string('auth_provider')->nullable();
            $table->string('table_name')->nullable();
            $table->string('command', 30)->nullable();
            $table->string('ip',45)->nullable();
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
        Schema::dropIfExists('logs');
    }
}
