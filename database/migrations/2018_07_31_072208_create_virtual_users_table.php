<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->integer('domain_id')->unsigned();
            $table->foreign('domain_id')->references('id')->on('virtual_domains')->onDelete('cascade');
            $table->string('email')->unique();
            $table->string('password');
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
        Schema::dropIfExists('virtual_users');
    }
}
