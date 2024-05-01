<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceitemsController extends Controller
{
    //
    public function add(Request $request)
    {
        $invoiceitem = new \App\Models\Invoiceitem();
        $invoiceitem->invoice_id = $request->invoice_id;
        $invoiceitem->item = $request->item;
        $invoiceitem->quantity = $request->quantity;
        $invoiceitem->price = $request->price;
        $invoiceitem->save();
        return response()->json([
            'success' => true,
            'invoiceitem' => $invoiceitem
        ]);
    }
}
