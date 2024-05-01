<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasTable('invoices')) {
            Schema::table(
                'invoices',
                function (Blueprint $table) {
                    if (Schema::hasColumn('invoices', 'firstpayment')) {
                        return;
                    }
                    $table->double('firstpayment', 8, 2)->default(0.00)->after('amount');
                }
            );
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
