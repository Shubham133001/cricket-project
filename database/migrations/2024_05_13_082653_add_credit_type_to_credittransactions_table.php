<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditTypeToCredittransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credittransactions', function (Blueprint $table) {
            $table->integer('cradit_type')->nullable()->comment("1 for credit , 2 for debit");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credittransactions', function (Blueprint $table) {
            $table->dropColumn('cradit_type');
        });
    }
}
