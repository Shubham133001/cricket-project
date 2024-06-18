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

            if ($mail) {
                $maildata = array();
                foreach ($mail as $m) {
                    $maildata[$m->setting] = $m->value;
                }


                $config = array(
                    'driver'     => 'smtp',
                    'host'       => isset($maildata['smtphost']) ? $maildata['smtphost'] : '',
                    'port'       => isset($maildata['smtpport']) ? $maildata['smtpport'] : '',
                    'encryption' => isset($maildata['smtpencryption']) ? $maildata['smtpencryption'] : '',
                    'username'   => isset($maildata['smtpusername']) ? $maildata['smtpusername'] : '',
                    'password'   => isset($maildata['smtppassword']) ? $maildata['smtppassword'] : '',
                    'from' => [
                        'address' => isset($maildata['smtpusername']) ? $maildata['smtpusername'] : '',
                        'name' => isset($storename->value) ? $storename->value : '',
                    ],
                );


                 Config::set('mail', $config);               
            }
        }
    }
}
