<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
        if (\Schema::hasTable('settings')) {
            $mail = DB::table('settings')->where('setting', 'like', '%smtp%')->get();
            $storename  = DB::table('settings')->where('setting', 'name')->first();

            $mail = \App\Models\Setting::where('setting', 'smtp_details')->first();
            $storename = \App\Models\Setting::where('setting', 'name')->first();
            $storeemail = \App\Models\Setting::where('setting', 'email')->first();

            if ($mail) //checking if table is not empty
            {
                $maildata = json_decode($mail->value, true);
                $config = array(
                    'driver' => isset($maildata['driver']) ? $maildata['driver'] : 'sendmail',
                    'host' => '',
                    'port' => '',
                    'from' => array('address' => $storeemail->value, 'name' => $storename->value),
                    'encryption' => '',
                    'username' => '',
                    'password' => '',
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );
                if (isset($maildata['driver'])) {

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
                    }
                }
                Config::set('mail', $config);
            }
        }
    }
}
