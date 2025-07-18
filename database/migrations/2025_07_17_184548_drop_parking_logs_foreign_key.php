<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropParkingLogsForeignKey extends Migration
{
    public function up()
    {
        Schema::table('parking_logs', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
        });
    }

    public function down()
    {
        Schema::table('parking_logs', function (Blueprint $table) {
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->onDelete('cascade');
        });
    }
}