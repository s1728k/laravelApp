<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attach', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pivot_table')->nullable();
            $table->string('pivot_field')->nullable();
            $table->integer('pivot_id')->nullable();
            $table->integer('attach_type_id')->nullable();
            $table->string('attach_name')->nullable();
            $table->text('path')->nullable();
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
        Schema::dropIfExists('attach');
    }
}
