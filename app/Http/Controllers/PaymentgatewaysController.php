<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentgatewaysController extends Controller
{
    //
    public function addpaymentgateways(Request $request)
    {
        $paymentgateway = new \App\Models\Paymentgateway();
        $paymentgateway->gateway = $request->gateway;
        $paymentgateway->setting = 'status';
        $paymentgateway->value = 'Active';
        $paymentgateway->save();
        return response()->json([
            'success' => true,
            'message' => 'Payment Gateway Added'
        ]);
    }
    public function removepaymentgateways(Request $request)
    {
        $paymentgateway = new \App\Models\Paymentgateway();
        $paymentgateway->where('gateway', $request->gateway)->where('setting', 'status')->delete();
        return response()->json([
            'success' => true,
            'message' => 'Payment Gateway Removed'
        ]);
    }
    public function getpaymentgateways()
    {
        // read all files in app/Http/Controller/gateways
        $files = scandir(app_path() . '/Http/Controllers/gateways');
        $gateways = [];
        foreach ($files as $key => $value) {
            if ($value != '.' && $value != '..') {
                $gateways[] = str_replace('.php', '', $value);
            }
        }
        $finalgateways = [];
        foreach ($gateways as $key => $value) {
            $finalgateways[$value]['status'] = 'Inactive';
            $configdata = \App\Models\Paymentgateway::where('gateway', $value)->get();
            $config = [];

            foreach ($configdata as $key1 => $value1) {
                $config[$value1->setting] = $value1->value;
                if ($value1->setting == 'status') {
                    $finalgateways[$value]['status'] = $value1->value;
                }
            }
            $finalgateways[$value]['config'] = $config;
        }
        return response()->json([
            'success' => true,
            'gateways' => $finalgateways
        ]);
    }
    public function getgateways()
    {
        // read all files in app/Http/Controller/gateways
        $files = scandir(app_path() . '/Http/Controllers/gateways');
        $gateways = [];
        foreach ($files as $key => $value) {
            if ($value != '.' && $value != '..') {
                $gateways[] = str_replace('.php', '', $value);
            }
        }
        foreach ($gateways as $key => $value) {
            $isActive = \App\Models\Paymentgateway::where('gateway', $value)->where('setting', 'status')->first();
            if ($isActive) {
                $gateways[$key] = $value;
            } else {
                unset($gateways[$key]);
            }
        }

        return response()->json([
            'success' => true,
            'gateways' => $gateways
        ]);
    }
    public function getconfig($gateway)
    {
        $gateway = ucfirst($gateway);
        $configdata = \App\Models\Paymentgateway::where('gateway', $gateway)->get();

        // call config function from gateway file
        $gatewayconfig = \App::call('App\\Http\\Controllers\\gateways\\' . $gateway . '@config');

        $config = [];
        foreach ($gatewayconfig as $key => $value) {
            if ($value['Type'] == 'dropdown') {
                $value['Options'] = array_keys($value['Options']);
            }
            foreach ($configdata as $key1 => $value1) {

                if ($value1->setting == $key) {

                    $gatewayconfig[$key]['value'] = $value1->value;
                }
            }
        }

        return response()->json([
            'success' => true,
            'config' => $gatewayconfig
        ]);
    }
    public function saveconfig(Request $request)
    {
        $gateway = ucfirst($request['gateway']);
        foreach ($request['config'] as $key => $value) {
            // check if key exists
            $setting = \App\Models\Paymentgateway::where('gateway', $gateway)->where('setting', $key)->first();

            if ($setting) {
                $setting->value = $value['value'];
                $setting->save();
            } else {
                if (isset($value['value'])) {
                    $setting = new \App\Models\Paymentgateway();
                    $setting->gateway = $gateway;
                    $setting->setting = $key;
                    $setting->value = $value['value'];
                    $setting->save();
                }
            }
        }

        $configdata = \App\Models\Paymentgateway::where('gateway', $gateway)->get();
        $config = [];
        foreach ($configdata as $key => $value) {
            $config[$value->setting] = $value->value;
        }
        return response()->json([
            'success' => true,
            'config' => $config
        ]);
    }
}
