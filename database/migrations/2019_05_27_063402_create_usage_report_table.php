<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsageReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usage_report', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('app_id');
            $table->unsignedInteger('api_calls')->default(0);
            $table->unsignedInteger('emails_sent')->default(0);
            $table->unsignedInteger('push_sent')->default(0);
            $table->unsignedInteger('chat_messages')->default(0);
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
        Schema::dropIfExists('usage_report');
    }
}
