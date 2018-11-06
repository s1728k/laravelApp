<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('database_name')->nullable()->index();
            $table->boolean('private')->default(false);
            $table->integer('table_size')->nullable();
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
        Schema::dropIfExists('table_groups');
    }
}

// REQUIRED DISK SPACE
// REQUIRED MINIMUM PHP VERSION
// REQUIRED MINIMUM MYSQL VERSION
// URL
// LANGUAGE
// ADMINISTRATOR USERNAME*
// ADMINISTRATOR PASSWORD*
// ADMINISTRATOR EMAIL
// WEBSITE TITLE
// WEBSITE TAGLINE
// APPLICATION UPDATES
