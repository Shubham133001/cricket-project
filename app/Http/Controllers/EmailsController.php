<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class EmailsController extends Controller
{
    //

    public function sendEmail($request)
    {
        $to_name = $request->to_name;
        $to_email = $request->to_email;
        $subject = $request->subject;
        $from = $request->from;
        $from_email = $request->from_email;
        // email queue
        Mail::queue('emails.email', $request->all(), function ($message) use ($to_name, $to_email, $subject, $from, $from_email) {
            $message->to($to_email, $to_name)
                ->subject($subject)
                ->from($from_email, $from);
        });
    }
}
