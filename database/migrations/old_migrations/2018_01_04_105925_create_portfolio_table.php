<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfolioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name')->nullable();
            $table->string('project_type')->nullable();
            $table->text('icon_path')->nullable();
            $table->integer('images_id')->nullable();
            $table->integer('attachments_id')->nullable();
            $table->text('abstract')->nullable();
            $table->text('description')->nullable();
            $table->integer('likes')->nullable();
            $table->float('mean_rating')->nullable();
            $table->integer('comments_id')->nullable();
            $table->text('share_link')->nullable();
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
        Schema::dropIfExists('portfolio');
    }
}
