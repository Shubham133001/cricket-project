<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function addpayments(Request $request)
    {
        try {
            $payment = new \App\Models\Payment;
            $payment->invoice_id = $request->id;
            $payment->transactionid = $request->transaction_id;
            $payment->currency = $request->currency;
            $payment->amount = $request->amount;
            $payment->method = $request->method;
            $payment->user_id = $request->user_id;
            $payment->save();
            // update invoice status
            $invoice = \App\Models\Invoice::find($request->id);
            $invoice->status = 1;
            $invoice->payment_id = $payment->id;
            //$invoice->amount = $request->amount;
            $invoice->save();

            if ($invoice->amount >= $request->amount) {
                $status = "Paid";
            } else {
                $status = "Partial paid";
            }

            // update booking status
            $bookings = \App\Models\Booking::where('invoice_id', $request->id)->get();
            foreach ($bookings as $book) {
                \App\Models\Booking::where('id', $book->id)->update(['status' => 'Approved', 'payment_status' => $status]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Invoice paid successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function paynow(Request $request)
    {
        try{
        $data = [];
        $gateway = $request->gateway;
        // $data = [];
        // foreach ($config as $key => $value) {
        //     $data[$key] = $request->$key;
        // }
        $user = auth()->user();
        if ($user) {
            $data['phone'] = $user->phone;
            $data['email'] = $user->email;
            $data['name'] = $user->name;
        }
        $invoicedata = \App\Models\Invoice::find($request->invoiceid);
        if ($invoicedata) {
            $data['invoiceid'] = $invoicedata->id;
            $data['amount'] = ($invoicedata->status == 0) ? $invoicedata->firstpayment : (($invoicedata->status == 2) ? $invoicedata->amount - $invoicedata->firstpayment : $invoicedata->amount);
            $data['description'] = $invoicedata->description;
        }

        $data['invoiceid'] = $request->invoiceid;
        $data['callbackurl'] = 'http://localhost:8000/api/callback/' . $gateway . '/' . $request->invoiceid;
        $data['redirecturl'] =
            'http://localhost:8000/api/callback/' . $gateway . '/' . $request->invoiceid;
        $resp = \App::call('App\\Http\\Controllers\\gateways\\' . $gateway . '@redirect', ['data' => $data]);
        return response()->json([
            'success' => true,
            'data' => $resp
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }

    public function callback(Request $request)
    {
        try{
        $gateway = $request->gateway;
        $invoiceid = request()->segment(4);
        $data = $request->all();
        $code = $data['code'];

        if ($code == 'PAYMENT_SUCCESS') {

            $transactionId = $data['transactionId'];
            $amount = $data['amount'] / 100;
            $refid = $data['providerReferenceId'];
            $checksum = $data['checksum'];

            // update invoice and add payment
            $invoice = \App\Models\Invoice::find($invoiceid);
            $payment = new \App\Models\Payment();
            // check if payment already exists
            $count = $payment->where('transactionid', $transactionId)->count();
            if ($count > 0) {
                header('Location: /invoice/' . $invoiceid);
                return;
            }
            $payment->invoice_id = $invoiceid;
            $payment->amount = $amount;
            $payment->transactionid = $transactionId;
            $payment->method = $gateway;
            $payment->status = 'paid';
            $payment->currency = 'INR';
            $payment->user_id = $invoice->user_id;
            $payment->save();
            $status = 'Paid';
            $payment_status = 'Paid';
            if ($invoice->status == 0) {
                $invoice->status = 2;
            } else if ($invoice->status == 2) {
                $invoice->status = 1;
            }
            if ($invoice->status == 0) {
                $status = 'Booked';
                $payment_status = 'Partial Paid';
            } else if ($invoice->status == 2) {
                $status = 'Completed';
                $payment_status = 'Paid';
            }
            $invoice->save();

            // update booking status
            $bookings = \App\Models\Booking::where('invoice_id', $invoiceid)->get();

            foreach ($bookings as $book) {
                \App\Models\Booking::where('id', $book->id)->update(['status' => $status, 'payment_status' => $payment_status]);
            }
        }

        $resp = \App::call('App\\Http\\Controllers\\gateways\\' . $gateway . '@callback', ['data' => $request->all()]);
        return response()->json([
            'success' => true,
            'data' => $resp
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }

    public function getpayments(Request $request)
    {

        try {
            $options = $request->options;

            $limit = $options['itemsPerPage'] ?? 10;
            $page = $request->page ?? 1;
            $search = $request->search ?? '';

            $resp = \App\Models\Payment::with('user')
                ->where(function ($query) use ($search) {
                    if ($search) {
                        $query->whereHas('user', function ($userQuery) use ($search) {
                            $userQuery->orWhere('phone', 'like', '%' . $search . '%')
                            ->orWhere('name', 'like', '%' . $search . '%');
                        })
                            ->orWhere('amount', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('method', 'like', '%' . $search . '%')
                            ->orWhere('transactionid', 'like', '%' . $search . '%')
                            ->orWhere('created_at', 'like', '%' . $search . '%');
                    }
                })
                ->orderByDesc('created_at')
                ->paginate($limit);

            return response()->json([
                'success' => true,
                'payments' => $resp
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    public function todaytransactions()
    {
        try{
        $payments = \App\Models\Payment::with('user')->whereDate('created_at', date('Y-m-d'))->get();
        return response()->json([
            'success' => true,
            'payments' => $payments
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }

    public function deletetransaction($id)
    {
        try{
        $payment = \App\Models\Payment::find($id);
        if ($payment) {
            $payment->delete();
            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Payment not found'
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }
}
