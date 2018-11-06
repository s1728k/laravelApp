<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mime');
            $table->unsignedInteger('size');
            $table->string('pivot_table');
            $table->string('pivot_field');
            $table->unsignedInteger('pivot_id');
            $table->unsignedInteger('sr_no');
            $table->string('path');
            $table->timestamps();
            $table->unique(['pivot_table', 'pivot_field', 'pivot_id', 'sr_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
