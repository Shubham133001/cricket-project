<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Invoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create invoice table
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer("slot_id");
            $table->integer("payment_id")->nullable()->comment("payment id from payment table");
            $table->integer("user_id")->default(0);
            $table->integer('status')->comment("0 for unpaid ,1 for paid, 2 for partial paid");
            $table->double('amount', 8, 2)->default(0.00);
            $table->double('firstpayment', 8, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->string('created_by')->nullable();
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
        //
    }
}
