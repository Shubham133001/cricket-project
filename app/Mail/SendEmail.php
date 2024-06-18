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
    protected $url;
    public function __construct($details)
    {
        $this->url = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $storename = Setting::where('setting','name')->first();
        $logo = Setting::where('setting','logo')->first();
        $storeDetails = ['logo' => $logo->value, 'companyname' => $storename->value, 'msg' => "Forgot Email".'<br />','url'=>$this->url];
        return $this->view('emails.email', $storeDetails)->subject("Reset password");
    }
}
