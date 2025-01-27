<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `users` MODIFY `phone` VARCHAR(255) NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `password` VARCHAR(255) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `users` MODIFY `phone` VARCHAR(255) NOT NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `password` VARCHAR(255) NOT NULL;');
    }
}
