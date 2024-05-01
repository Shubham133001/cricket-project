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
