<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablesTrack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables_track', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name')->nullable();
            $table->string('table_group_name')->nullable()->index();
            $table->string('db_name')->nullable()->index();
            $table->text('table_description')->nullable();
            $table->text('keywords')->nullable();
            $table->boolean('private')->default(false)->index();
            $table->text('field_indexes')->nullable();
            $table->text('fillable')->nullable();
            $table->text('hidden')->nullable();
            $table->string('created_by')->nullable()->index();
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
        Schema::dropIfExists('tables_track');
    }
}
