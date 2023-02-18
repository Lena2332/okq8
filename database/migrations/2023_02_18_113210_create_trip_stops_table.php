<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_stops', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->bigInteger('start_destination_id')->unsigned();
            $table->bigInteger('end_destination_id')->unsigned();
            $table->bigInteger('stop_destination_id')->unsigned();
            $table->integer('position')->unsigned();
            $table->foreign('start_destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->foreign('end_destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->foreign('stop_destination_id')->references('id')->on('destinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_stops');
    }
};
