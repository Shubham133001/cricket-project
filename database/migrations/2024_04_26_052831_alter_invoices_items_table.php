<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvoicesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasTable('invoice_items')) {
            Schema::table(
                'invoice_items',
                function (Blueprint $table) {
                    if (Schema::hasColumn('invoice_items', 'slot_id')) {
                        return;
                    }
                    $table->integer('slot_id')->after('id');
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
