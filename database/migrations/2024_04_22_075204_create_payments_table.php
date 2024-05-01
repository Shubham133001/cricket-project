<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string("transactionid");
            $table->integer("invoice_id");
            // $table->integer("slot_id");
            $table->double("amount", 8, 2)->default(0.00);
            $table->string("currency")->default("INR");
            $table->string("status")->default('paid');
            $table->string("method")->nullable();
            $table->integer("user_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
