<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentRateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_rate_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->nullable();
            $table->date('rent_effective_from')->nullable();
            $table->decimal('total_rent_agreed', 8, 2)->nullable();
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
        Schema::dropIfExists('rent_rate_history');
    }
}
