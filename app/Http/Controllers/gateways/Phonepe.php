<?php

namespace App\Http\Controllers\gateways;

use Illuminate\Http\Request;
use Dipesh79\LaravelPhonePe\LaravelPhonePe;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class Phonepe extends Controller
{
    //
    public function config()
    {
        return [
            'GatewayName' => array(
                'Type' => 'System',
                'value' => 'PhonePe'
            ),
            'merchantId' => array(
                'FriendlyName' => 'Merchant ID',
                'Description' => 'Enter your PhonePe Merchant ID',
                'Type' => 'text'

            ),
            'merchantUserId' => array(
                'FriendlyName' => 'Merchant User ID',
                'Description' => 'Enter your PhonePe Merchant user ID',
                'Type' => 'text'

            ),
            'merchantKey' => array(
                'FriendlyName' => 'Merchant Key',
                'Description' => 'Enter your PhonePe Merchant Key',
                'Type' => 'text'
            ),
            'merchantSalt' => array(
                'FriendlyName' => 'Merchant Salt',
                'Description' => 'Enter your PhonePe Merchant Salt',
                'Type' => 'text'
            ),
            'mode' => array(
                'FriendlyName' => 'Mode',
                'Description' => 'Select the mode',
                'Type' => 'dropdown',
                'Options' => [
                    [
                        'text' => 'Sandbox',
                        'value' => 'sandbox'
                    ],
                    [
                        'text' => 'Live',
                        'value' => 'live'
                    ]
                ]
            )
        ];
    }
    public function redirect($data)
    {

        $configadata = \App\Models\Paymentgateway::where('gateway', 'Phonepe')->get();

        foreach ($configadata as $key => $value) {
            $data[$value->setting] = $value->value;
        }
        // generate 35 characters transaction id with hyphen and underscore
        $transactionId = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 35)), 0, 35);
        $merchantId = (isset($data['merchantId'])) ? $data['merchantId'] : '';
        $merchantuserId = (isset($data['merchantUserId'])) ? $data['merchantUserId'] : '';
        $merchantKey = (isset($data['merchantKey'])) ? $data['merchantKey'] : '';
        $merchantSalt = (isset($data['merchantSalt'])) ? $data['merchantSalt'] : '';
        $mode = (isset($data['mode'])) ? $data['mode'] : '';
        $orderId = (isset($data['orderId'])) ? $data['orderId'] : '';
        $invoiceid = (isset($data['invoiceid'])) ? $data['invoiceid'] : '';
        $amount = (isset($data['amount'])) ? $data['amount'] : 0;
        $phone = (isset($data['phone'])) ? $data['phone'] : '';
        $email = (isset($data['email'])) ? $data['email'] : '';
        $name = (isset($data['name'])) ? $data['name'] : '';
        $description = (isset($data['description'])) ? $data['description'] : '';
        $callbackUrl = (isset($data['callbackurl'])) ? $data['callbackurl'] : '';
        $redirectUrl = (isset($data['redirecturl'])) ? $data['redirecturl'] : '';
        // $phonepe = new LaravelPhonePe(
        //     $merchantId,
        //     $merchantuserId,
        //     $merchantKey,
        //     $merchantSalt,
        //     $mode,
        //     $callbackUrl
        // );
        //amount, phone number, callback url, unique merchant transaction id

        $url = self::makePayment(
            $amount,
            $phone,
            $redirectUrl,
            $transactionId,
            $merchantId,
            $merchantuserId,
            $merchantKey,
            $merchantSalt,
            $mode,
            $callbackUrl,
            $invoiceid
        );
        return response()->json(['url' => $url]);
    }

    public function makePayment(
        $amount,
        $phone,
        $redirectUrl,
        $merchantTransactionId,
        $merchantId,
        $merchantuserId,
        $merchantKey,
        $merchantSalt,
        $mode,
        $callbackUrl,
        $invoiceid
    ) {

        $event_payload = [
            "merchantId" => $merchantId,
            "merchantTransactionId" => $merchantTransactionId,
            "merchantUserId" => $merchantuserId,
            "amount" => $amount * 100,
            "redirectUrl" => $redirectUrl,
            "redirectMode" => "POST",
            "callbackUrl" => $callbackUrl,
            'mobileNumber' => $phone,
            "paymentInstrument" => [
                "type" => "PAY_PAGE",
            ],
            "param1" => $invoiceid
        ];
        //echo "<pre>";
        // print_r($amount); die;
        $encoded_payload = base64_encode(json_encode($event_payload));
        $saltKey = $merchantSalt;
        $saltIndex = $merchantKey;
        $encode = $event_payload;
        $string = $encoded_payload . "/pg/v1/pay" . $saltKey;
        $sha256 = hash("sha256", $string);
        $finalXHeader = $sha256 . "###" . $saltIndex;
        $header = [
            "Content-Type" => "application/json",
            "X-VERIFY" => $finalXHeader,
        ];

        $headers = ["Content-Type: application/json", "X-VERIFY:" . $finalXHeader];
        if ($mode == 'live') {
            $phone_pay_url = "https://api.phonepe.com/apis/hermes/pg/v1/pay";
        } else {
            $phone_pay_url = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";
        }

        $ch = curl_init($phone_pay_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode(["request" => $encoded_payload])
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $decodeJson = json_decode($response);

        return $decodeJson->data->instrumentResponse->redirectInfo->url;
        // return $decodeJson->data->instrumentResponse->intentUrl;
    }

    public function getTransactionStatus(array $request)
    {
        $configadata = \App\Models\Paymentgateway::where('gateway', 'Phonepe')->get();

        foreach ($configadata as $key => $value) {
            $data[$value->setting] = $value->value;
        }
        // generate 35 characters transaction id with hyphen and underscore
        // $transactionId = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 35)), 0, 35);
        $merchantId = (isset($data['merchantId'])) ? $data['merchantId'] : '';
        $merchantuserId = (isset($data['merchantUserId'])) ? $data['merchantUserId'] : '';
        $merchantKey = (isset($data['merchantKey'])) ? $data['merchantKey'] : '';
        $merchantSalt = (isset($data['merchantSalt'])) ? $data['merchantSalt'] : '';
        $mode = (isset($data['mode'])) ? $data['mode'] : '';
        $finalXHeader = hash('sha256', '/pg/v1/status/' . $merchantId . '/' . $request['transactionId'] . $merchantKey) . '###' . $merchantSalt;
        $baseUrl = $mode == 'live' ? 'https://api.phonepe.com/apis/hermes' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseUrl . '/pg/v1/status/' . $merchantId . '/' . $request['transactionId'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'accept: application/json',
                'X-VERIFY: ' . $finalXHeader,
                'X-MERCHANT-ID: ' . $request['transactionId']
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (json_decode($response)->success) {
            return true;
        } else {
            return false;
        }
    }

    public function callback($data)
    {
        // get url last segment
        $invoiceid = request()->segment(4);

        // $response = self::getTransactionStatus($data);
        // if ($response) {
        //     // update invoice and add payment
        //     $invoice = \App\Models\Invoice::find($data['param1']);
        //     $payment = new \App\Models\Payment();
        //     $payment->invoice_id = $data['param1'];
        //     $payment->amount = $data['amount'] / 100;
        //     $payment->save();
        //     $invoice->status = 1;
        //     $invoice->save();
        // }
        header('Location: /invoice/' . $invoiceid);
        exit;
    }
}
