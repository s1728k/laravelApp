<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rent_house_id')->nullable();
            $table->string('name')->nullable();
            $table->decimal('Phone', 15)->nullable();
            $table->integer('attach_images_id')->nullable();
            $table->integer('rent_history_id')->nullable();
            $table->integer('rent_rate_history_id')->nullable();
            $table->date('join_date')->nullable();
            $table->decimal('total_rent_agreed', 8, 2)->nullable();
            $table->date('rent_effective_from')->nullable();
            $table->decimal('previous_dues', 8, 2)->nullable();
            $table->decimal('advance_received', 8, 2)->nullable();
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
        Schema::dropIfExists('tenants');
    }
}
