<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use PDF;

class InvoicesController extends Controller
{
    //
    public function index()
    {
        try {
            $invoices = Invoice::with('user', 'payment', 'created_by')->get();
            return view('admin.invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            $invoice = new Invoice();
            $invoice->slot_id = $request->slot_id;
            $invoice->user_id = $request->user_id;
            $invoice->status = $request->status;
            $invoice->amount = $request->amount;
            $invoice->description = $request->description;
            $invoice->created_by = Auth::user()->id;
            $invoice->save();
            return redirect()->route('invoices.index');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
        try {
            $invoice = Invoice::with(['user', 'payment' => function ($query1) {
                $query1->with('user');
            }, 'items' => function ($query) {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        try {
            $invoice = Invoice::with(['items' => function ($query) {
                $query->with('slots');
            }])->where('id', $id)->first();
            if ($invoice) {
                return response()->json([
                    'success' => true,
                    'invoice' => $invoice
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getallinvoices(Request $request)
    {
        try {
            // paginate the invoices
            $options = $request->options;
            $limit = $options['itemsPerPage'] ?? 10;
            $page = $request->page ?? 1;
            $search = $request->search ?? '';
            $resp = Invoice::with('user', 'payment')
                ->where(function ($query) use ($search) {
                    if ($search) {
                        $query->whereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%');
                        })
                            ->orWhere('amount', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('created_at', 'like', '%' . $search . '%');
                    }
                })
                ->orderByDesc('created_at')
                ->paginate($limit);

            return response()->json([
                'success' => true,
                'invoices' => $resp
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function changegateway(Request $request)
    {
        try {
            $invoice = Invoice::find($request->invoice_id);
            $invoice->gateway = $request->gateway;
            $invoice->save();
            return response()->json([
                'success' => true,
                'message' => 'Payment gateway changed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $invoice = Invoice::find($request->id);
            foreach ($request->all() as $key => $value) {
                $invoice->$key = $value;
            }
            $invoice->save();
            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function downloadPdf($id)
    {
        try {
            $invoice = self::getInvoiceById($id);

            $item = $invoice->original['invoice'];
            $storedetaildata = \App\Models\Setting::get()->toArray();
            $storedetail = [];

            foreach ($storedetaildata as $store) {
                $storedetail[$store['setting']] = $store['value'];
            }

            view()->share(['item' => $item, 'storedetail' => $storedetail]);
            $pdf = PDF::loadView('pdfview');
            return $pdf->download('invoice-' . $item->id . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function viewpdf($id)
    {
        try {
            $invoice = self::getInvoiceById($id);

            $item = $invoice->original['invoice'];
            // echo '<pre>';
            // print_r($item);
            // die();
            $storedetaildata = \App\Models\Setting::get()->toArray();
            $storedetail = [];

            foreach ($storedetaildata as $store) {
                $storedetail[$store['setting']] = $store['value'];
            }

            view()->share(['item' => $item, 'storedetail' => $storedetail]);
            $pdf = PDF::loadView('pdfview');
            return $pdf->stream('invoice-' . $item->id . '.pdf', array('Attachment' => 0));
            exit(0);
        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = Invoice::find($request->id);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
