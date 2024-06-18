<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $url = url('/auth/resetpassword?token=' . $this->token . '&email=' . $notifiable->getEmailForPasswordReset());
        $storedetails = getstoredetails();
        $msg = "<div style='text-align: center'><h2>You are receiving this email because we received a password reset request for your account.</h2><br /><a href='" . $url . "' style='background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;'>Reset Password</a><br /><br />If you did not request a password reset, no further action is required.</div>";
        return (new MailMessage)->subject("Reset Password")->view(
            'emails.email',
            [
                'msg' =>  $msg,
                'logo' => $storedetails['logo'],
                'companyname' => $storedetails['name']
            ]
        );
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
