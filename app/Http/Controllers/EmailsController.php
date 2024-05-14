<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;


class EmailsController extends Controller
{
    //  
    use Queueable;
    public function sendEmail($request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
