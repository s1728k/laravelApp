<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentHouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_house', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenants_id')->nullable();
            $table->integer('house_images_id')->nullable();
            $table->text('house_address')->nullable();
            $table->tinyInteger('size')->nullable();
            $table->decimal('solo_rent_price', 8, 2)->nullable();
            $table->decimal('rent_price_shared', 8, 2)->nullable();
            $table->decimal('advance', 8, 2)->nullable();
            $table->decimal('advance_shared', 8, 2)->nullable();
            $table->integer('persons_allowed')->nullable();
            $table->boolean('allow_sharing')->nullable();
            $table->string('availability')->nullable();
            $table->tinyInteger('rent_mode')->nullable();
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
        Schema::dropIfExists('rent_house');
    }
}
