<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')->unsigned()->index();
            $table->string("name");
            $table->string("auth_providers");
            $table->string("tables");
            $table->string("commands");
            $table->text("fillables")->nullable();
            $table->text("hiddens")->nullable();
            $table->text("mandatory")->nullable();
            $table->text("joins")->nullable();
            $table->text("filters")->nullable();
            $table->string("specials")->nullable();
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
        Schema::dropIfExists('queries');
    }
}
