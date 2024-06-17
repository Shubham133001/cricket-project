<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $token;
    protected $email;
    protected $template;
    protected $firstname;
   // protected $lastname;

    public function __construct($token, $firstname, $email, $template)
    {
        $this->token = $token;
        $this->email = $email;
        $this->template = $template;
        $this->firstname = $firstname;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $storedetails = getStoreDetails();
        $msg = str_replace('{$firstname}', $this->firstname, $this->template['message']);
       // $msg = str_replace('{$lastname}', $this->lastname, $msg);
        $msg = str_replace('{$resetpasswordurl}', url('/auth/reset-password?token=' . $this->token . '&email=' . $this->email . ''), $msg);
        $msg = str_replace('{$forgetpasswordurl}',  url('/auth/forgot-password'), $msg);
        $msg = str_replace('{$appurl}',url('/'), $msg);
        $msg = str_replace('{$companyname}', "Best Cricket Academy", $msg);
        return (new MailMessage)->subject($this->template->subject)->view(
            'emails.email',
            [
                'msg' =>  $msg,
                'logo' => $storedetails['logo'],
                'companyname' =>"Best Cricket Academy"
            ]
        );
        // return (new MailMessage)
        //     ->line('You are receiving this email because we received a password reset request for your account..')
        // ->action('Reset Password', url('/auth/reset-password?token=' . $this->token . '&email=' . $this->email . ''))
        //     ->line('This password reset link will expire in 60 minutes.')
        //     ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
