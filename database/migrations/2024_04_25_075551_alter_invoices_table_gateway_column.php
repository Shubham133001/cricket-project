<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvoicesTableGatewayColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //check if invoices table exists
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                // check if gateway column not exists
                if (Schema::hasColumn('invoices', 'gateway')) {
                    return;
                }
                $table->string('gateway')->after('status');
            });
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
