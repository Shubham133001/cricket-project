<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\CreditsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\SlotsController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\CommunicationsController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\AbandonedbookingsController;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Slot;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class Cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Daily:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Daily Cron Jobs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // check pending bookings which were more then 20 minutes
        $bookings = Booking::where('status', 'Pending')->where('payment_status', 'Pending')->where('created_at', '<', now()->subMinutes(20))->get();
        // show message 
        echo 'Checking ' . count($bookings) . ' pending bookings which were more then 20 minutes' . PHP_EOL;
        foreach ($bookings as $booking) {
            // add abandoned booking
            $abandonedbooking = new AbandonedbookingsController();
            // create new request
            $request = new Request();
            $request->replace([
                'slot_id' => $booking->slot_id,
                'user_id' => $booking->user_id,
                'booking_date' => $booking->date
            ]);
            $abandonedbooking->addabandonedbookings($request);
            // remove booking
            $booking->delete();

            // send email to user
            $user = User::find($booking->user_id);
            $slot = Slot::find($booking->slot_id);
            $email = new EmailsController();
            $emaildata = [
                'email' => $user->email,
                'subject' => 'Booking Cancelled',
                'message' => 'Your booking for ' . $slot->name . ' has been cancelled as you did not complete the booking process within 20 minutes.',
                'to_name' => $user->name,
                'to_email' => $user->email
            ];
            $email->sendEmail($emaildata);
        }
        // check if booked slots are less than the allowed bookings and refund as credits
        echo 'Checking if booked slots are less than the allowed bookings and refund as credits' . PHP_EOL;
        $slots = Slot::all();
        // check the booking for each slot on same date
        echo 'Checking for bookings which were older then 24 Hours and not get the opponent to play' . PHP_EOL;
        $refunds = 0;
        foreach ($slots as $slot) {
            // echo now()->subHours(24) . PHP_EOL;
            $bookings = Booking::where('slot_id', $slot->id)->where('status', 'Approved')->whereDate('date', '<', now()->subHours(24))->get();
            // echo '--' . $slot->title . ' has ' . count($bookings) . ' bookings' . PHP_EOL;
            // check if the bookings are less than the allowed bookings
            // echo '-- have ' . $slot->bookings_allowed . ' allowed bookings and ' . count($bookings) . ' booked' . PHP_EOL;
            if (count($bookings) < $slot->bookings_allowed && count($bookings) > 0) {
                // echo 'Refunding credits for unused slots' . PHP_EOL;
                $refunds++;
                foreach ($bookings as $booking) {
                    // calculate the refund amount
                    $refundAmount = $slot->advanceprice;
                    // add credits to user
                    $user = User::find($booking->user_id);
                    $creditController = new CreditsController();
                    $request = new Request();
                    $request->replace([
                        'user_id' => $user->id,
                        'amount' => $refundAmount,
                        'description' => 'Refund for unused slots',
                        'created_by' => 0
                    ]);
                    $booking->payment_status = 'Refunded';
                    $booking->status = 'Cancelled';
                    $booking->save();
                    $creditController->addcredits($request);
                    // update credits in user table
                    $user->credits += $refundAmount;
                    $user->save();
                    // send email to user
                    $email = new EmailsController();
                    $emaildata = [
                        'email' => $user->email,
                        'subject' => 'Refund for unused slots',
                        'message' => 'Your booking for ' . $slot->name . ' on ' . $booking->date . ' has been refunded as the slot was not used. ' . $refundAmount . ' credits have been added to your account.'
                    ];
                    $email->sendEmail($emaildata);
                }
            }
            echo 'Refunded ' . $refunds . ' slots' . PHP_EOL;
        }
    }
}
