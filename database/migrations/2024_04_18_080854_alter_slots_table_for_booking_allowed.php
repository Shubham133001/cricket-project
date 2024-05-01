<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSlotsTableForBookingAllowed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //check if slots table exists
        if (Schema::hasTable('slots')) {
            // check if column exists
            if (!Schema::hasColumn('slots', 'bookings_allowed')) {
                Schema::table('slots', function (Blueprint $table) {
                    $table->integer('bookings_allowed')->default(1);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
