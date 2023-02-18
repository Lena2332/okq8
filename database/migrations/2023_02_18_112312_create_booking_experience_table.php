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
        Schema::create('booking_experience', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booking_id')->unsigned();
            $table->bigInteger('experience_id')->unsigned();
            $table->dateTime('from');
            $table->dateTime('to');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('experience_id')->references('id')->on('experiences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_experience');
    }
};
