<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookingsTableWithTeamId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //check if table exists
        if (Schema::hasTable('bookings')) {
            // check if column already exists
            if (!Schema::hasColumn('bookings', 'team_id')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->unsignedBigInteger('team_id')->nullable()->after('user_id');
                    $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
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
