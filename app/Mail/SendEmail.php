<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \App\Models\Setting;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $body;
    protected $title;
    public function __construct($details)
    {
        //
        $this->body = $details['body'];
        $this->title = $details['title'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $storedata = Setting::get();
        $storeDetails = ['logo' => $storedata['logo'], 'companyname' => $storedata['name'], 'msg' => "Forgot Email".'<br />'.$this->body];
        return $this->view('emails.email', $storeDetails);
    }
}
