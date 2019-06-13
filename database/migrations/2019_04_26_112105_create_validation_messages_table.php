<?php

use App\ValidationMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validation_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id')->nullable();
            $table->string('rule');
            $table->string('error_message')->nullable();
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
        Schema::dropIfExists('validation_messages');
    }

}
