<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Jobs\SendMail;
use App\Mail\SendEmail;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    //
    public function smtp()
    {
        $defaultStoreSettings = json_encode(Config::get('store.data'), true);
        $smtpDetails = Setting::where('setting', 'smtp_details')->first();
        $storename = (isset($smtpDetails->value) && !empty($smtpDetails->value)) ? $smtpDetails->value : $defaultStoreSettings;
        if ($smtpDetails != null) {
            return response()->json(
                [
                    'success' => true,
                    'smtpSettings' => json_decode($storename)
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'smtpSettings' => json_decode('{}')
                ]
            );
        }
    }

    public function smtpupdate(Request $request)
    {
        try {
            // check if store details exists
            $defaultStoreSettings = json_encode(Config::get('store.data'), true);
            $smtpDetails = Setting::where('setting', 'smtp_details')->first();
            $storename = (isset($smtpDetails->value) && !empty($smtpDetails->value)) ? $smtpDetails->value : $defaultStoreSettings;

            if ($smtpDetails) {
                $postdata = $request->all();
                unset($postdata['smtpDetails']);
                unset($postdata['success']);
                $smtpDetails->value = json_encode($postdata);
                $smtpDetails->save();
            } else {
                $postdata = $request->all();
                unset($postdata['smtpDetails']);
                unset($postdata['success']);
                $smtpDetails = new Setting();
                $smtpDetails->setting = 'smtp_details';
                $smtpDetails->value = json_encode($postdata);
                $smtpDetails->save();
            }
            return response()->json(
                [
                    'success' => true,
                    'smtpSettings' => json_decode($storename)
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ]
            );
        }
    }

    public function testSmtp()
    {
        $defaultStoreSettings = json_encode(Config::get('store.data'), true);
        $smtpDetails = Setting::where('setting', 'smtp_details')->first();
        $storename = (isset($smtpDetails->value) && !empty($smtpDetails->value)) ? $smtpDetails->value : $defaultStoreSettings;

        if ($smtpDetails != null) {
            $smtpDetails = json_decode($storename);
            $config = array(
                'driver'     => 'smtp',
                'host'       => isset($smtpDetails->host) ? $smtpDetails->host : '',
                'port'       => isset($smtpDetails->port) ? $smtpDetails->port : '',
                'from'       => array('address' => $smtpDetails->username, 'name' => 'Admin'),
                'encryption' => isset($smtpDetails->encryption) ? $smtpDetails->encryption : '',
                'username'   => isset($smtpDetails->username) ? $smtpDetails->username : '',
                'password'   => isset($smtpDetails->password) ? $smtpDetails->password : '',

            );
            Config::set('mail', $config);
            $details = [
                'title' => 'Mail from entmohali.com',
                'body' => 'This is for testing email using smtp'
            ];
            Mail::to('amarsingh.newspark@gmail.com')->send(new SendEmail($details));
            return response()->json(
                [
                    'success' => true,
                    'smtpSettings' => $smtpDetails
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'smtpSettings' => json_decode('{}')
                ]
            );
        }
    }

    public function sms()
    {
        $defaultStoreSettings = json_encode(Config::get('store.data'), true);

        $smsDetails = Setting::where('setting', 'sms_details')->first();
        $storename = (isset($smsDetails->value) && !empty($smsDetails->value)) ? $smsDetails->value : $defaultStoreSettings;

        if ($smsDetails != null) {
            return response()->json(
                [
                    'success' => true,
                    'smsSettings' => json_decode($storename)
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'smsSettings' => json_decode('{}')
                ]
            );
        }
    }
    public function smsupdate(Request $request)
    {
        try {
            // check if store details exists
            $defaultStoreSettings = json_encode(Config::get('store.data'), true);

            $smsDetails = Setting::where('setting', 'sms_details')->first();
            $storename = (isset($smsDetails->value) && !empty($smsDetails->value)) ? $smsDetails->value : $defaultStoreSettings;

            if ($smsDetails) {
                $postdata = $request->all();
                unset($postdata['smsDetails']);
                unset($postdata['success']);
                $smsDetails->value = json_encode($postdata);
                $smsDetails->save();
            } else {
                $postdata = $request->all();
                unset($postdata['smsDetails']);
                unset($postdata['success']);
                $smsDetails = new Setting();
                $smsDetails->setting = 'sms_details';
                $smsDetails->value = json_encode($postdata);
                $smsDetails->save();
            }
            return response()->json(
                [
                    'success' => true,
                    'smsSettings' => json_decode($storename)
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ]
            );
        }
    }
}
