<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number');
            $table->string('fullname');
            $table->string('no_hp');
            $table->string('vehicle_type');
            $table->string('license_plate');
            $table->boolean('is_scan')->default(false);
            $table->boolean('is_vip')->default(false);
            $table->dateTime('scan_date')->nullable();
            $table->unsignedBigInteger('group_seat_id');
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('event_id');
            $table->string('token');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('group_seat_id')->references('id')->on('group_seats');
            $table->foreign('participant_id')->references('id')->on('participants');
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrations');
    }
}
