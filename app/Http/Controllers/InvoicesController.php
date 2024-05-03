<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{
    //
    public function index()
    {
        $invoices = Invoice::with('user', 'payment', 'created_by')->get();
        return view('admin.invoices.index', compact('invoices'));
    }
    public function create(Request $request)
    {
        $invoice = new Invoice();
        $invoice->slot_id = $request->slot_id;
        $invoice->user_id = $request->user_id;
        $invoice->status = $request->status;
        $invoice->amount = $request->amount;
        $invoice->description = $request->description;
        $invoice->created_by = Auth::user()->id;
        $invoice->save();
        return redirect()->route('invoices.index');
    }
    public function payinvoice(Request $request)
    {
        try {
            // add payment in payment table
            $payment = new \App\Models\Payment();
            $payment->invoice_id = $request->id;
            $payment->transactionid = $request->payment_id;
            $payment->currency = $request->currency;
            $payment->amount = $request->amount;
            $payment->method = $request->method;
            $payment->user_id = $request->user_id;
            $payment->save();
            // update invoice status
            $invoice = Invoice::find($request->id);
            $invoice->status = 1;
            $invoice->payment_id = $payment->id;
            $invoice->save();
            // update booking status
            $bookings = \App\Models\Booking::where('invoice_id', $request->id)->get();
            foreach ($bookings as $book) {
                \App\Models\Booking::where('id', $book->id)->update(['status' => 'Approved', 'payment_status' => 'Paid']);
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
    public function getInvoiceById($id)
    {
        $invoice = Invoice::with(['user', 'payment', 'items' => function ($query) {
            $query->with('slots');
        }])->where('id', $id)->first();
        $payments = \App\Models\Payment::where('invoice_id', $id)->get();
        $totalpaid = 0;
        foreach ($payments as $payment) {
            $totalpaid += $payment->amount;
        }
        $balance = $invoice->amount - $totalpaid;
        $invoice->balance = $balance;

        if ($invoice) {
            return response()->json([
                'success' => true,
                'invoice' => $invoice
            ]);
        }
    }
    public function edit($id)
    {
        $invoice = Invoice::with(['items' => function ($query) {
            $query->with('slots');
        }])->where('id', $id)->first();
        if ($invoice) {
            return response()->json([
                'success' => true,
                'invoice' => $invoice
            ]);
        }
    }

    public function getallinvoices(Request $request)
    {

        // paginate the invoices
        $page = ($request->page) ? $request->page : 1;
        $limit = ($request->limit) ? $request->limit : 10;
        $search = ($request->search) ? $request->search : '';
        $invoices = Invoice::with('user', 'payment');

        if ($search != '') {
            $invoices->where('amount', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%');
        }
        $invoices->orderBy('created_at', 'desc');
        // $invoices->offset(($page - 1) * $limit);
        // $invoices->limit($limit);
        $resp = $invoices->paginate($limit);

        return response()->json([
            'success' => true,
            'invoices' => $resp
        ]);
    }
    public function changegateway(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        $invoice->gateway = $request->gateway;
        $invoice->save();
        return response()->json([
            'success' => true,
            'message' => 'Payment gateway changed successfully'
        ]);
    }
}
