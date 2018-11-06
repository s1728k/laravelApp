<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterTableListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_table_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name');
            $table->integer('app_id')->index();
            $table->text('table_description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('field_indexes');
            $table->text('fillable');
            $table->text('hidden')->nullable();
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
        Schema::dropIfExists('master_table_list');
    }
}
