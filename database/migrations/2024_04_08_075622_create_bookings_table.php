<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('slot_id');
            $table->string('date');
            $table->integer('invoice_id');
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected, Partially Booked
            $table->string('payment_status')->default('Pending'); // Pending, Paid, Failed, Refunded, Advance Paid
            $table->integer('bookings_allowed')->default(1);
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
        Schema::dropIfExists('bookings');
    }
}
