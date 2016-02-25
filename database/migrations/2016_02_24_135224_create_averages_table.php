<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('averages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->default(0);
            $table->integer('trip_id', false, true)->default(0);
            $table->float('km_per_liter')->default(0.00);
            $table->float('liters_per_hundred_km')->default(0.00);
            $table->integer('total_trip_km', false, true)->default(0);
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
        Schema::drop('averages');
    }
}
