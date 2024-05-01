<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Http\Controllers\admin\SettingsController;
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
            $settingController = new SettingsController();
            $mail = $settingController->smtp();
            if ($mail) //checking if table is not empty
            {
                $maildata = array();
                foreach ($mail as $m) {
                    $maildata[$m->name] = $m->value;
                }
                $config = array(
                    'driver'     => 'smtp',
                    'host'       => isset($maildata['host']) ? $maildata['host'] : '',
                    'port'       => isset($maildata['port']) ? $maildata['port'] : '',
                    // 'from'       => array('address' => $mail->from_address, 'name' => $mail->from_name),
                    'encryption' => isset($maildata['encryption']) ? $maildata['encryption'] : '',
                    'username'   => isset($maildata['username']) ? $maildata['username'] : '',
                    'password'   => isset($maildata['password']) ? $maildata['password'] : '',
                    // 'sendmail'   => '/usr/sbin/sendmail -bs',
                    // 'pretend'    => false,
                );
                Config::set('mail', $config);
            }
        }

    }
}
