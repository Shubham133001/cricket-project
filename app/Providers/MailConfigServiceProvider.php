<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Config;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // set smtp config from database
        if (\Schema::hasTable('settings')) {

            $mail = \App\Models\Setting::where('setting', 'smtp_details')->first();
            $storename = \App\Models\Setting::where('setting', 'name')->first();
            $storeemail = \App\Models\Setting::where('setting', 'email')->first();

            if ($mail) //checking if table is not empty
            {
                $maildata = json_decode($mail->value, true);

                if ($maildata['driver'] == 'smtp') {


                    $config = array(
                        'driver'     => isset($maildata['driver']) ? $maildata['driver'] : 'sendmail',
                        'host'       => isset($maildata['host']) ? $maildata['host'] : '',
                        'port'       => isset($maildata['port']) ? $maildata['port'] : '',
                        'from'       => array('address' => isset($maildata['username']) ? $maildata['username'] : '', 'name' => $storename->value),
                        'encryption' => isset($maildata['encryption']) ? $maildata['encryption'] : '',
                        'username'   => isset($maildata['username']) ? $maildata['username'] : '',
                        'password'   => isset($maildata['password']) ? $maildata['password'] : '',
                        // 'sendmail'   => '/usr/sbin/sendmail -bs',
                        // 'pretend'    => false,
                    );
                } else {
                    $config = array(
                        'driver' => 'sendmail',
                        'host' => '',
                        'port' => '',
                        'from' => array('address' => $storeemail->value, 'name' => $storename->value),
                        'encryption' => '',
                        'username' => '',
                        'password' => '',
                        'sendmail'   => '/usr/sbin/sendmail -bs',
                        'pretend'    => false,
                    );
                }
                Config::set('mail', $config);
            }
        }
    }
}
