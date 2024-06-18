<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CancellationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    //
    public function store()
    {
        try {
            $data = \App\Models\Setting::get();
            $data1 = [];
            foreach ($data as $key => $value) {
                $data1[$value->setting] = $value->value;
            }
            return response()->json([
                'success' => true,
                'storeDetails' => $data1
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function stats()
    {
        try {
            $data = [];
            $data['total_bookings'] = \App\Models\Booking::count();
            $data['total_categories'] = \App\Models\Category::count();
            $data['total_slots'] = \App\Models\Slot::count();
            $data['total_completebookings'] = \App\Models\Booking::where('status', 'Completed')->count();
            return response()->json([
                'success' => true,
                'stats' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeupdate(Request $request)
    {
        try {
            foreach ($request->all() as $key => $value) {
                // check if key exists
                $setting = \App\Models\Setting::where('setting', $key)->first();
                if ($setting) {
                    $setting->value = $value;
                    $setting->save();
                } else {
                    $setting = new \App\Models\Setting();
                    $setting->setting = $key;
                    $setting->value = $value;
                    $setting->save();
                }
            }
            $data1 = \App\Models\Setting::get();
            $data = [];
            foreach ($data1 as $key => $value) {
                $data[$value->setting] = $value->value;
            }
            return response()->json([
                'success' => true,
                'storeDetails' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function themesetting(Request $request)
    {
        //  $onlineusers = '';
        //  $action = request()->get('action');
        try {
            //if ($action == 'savesettings') {
            $exists = Storage::disk('local')->has('page_setting.json');
            if ($exists) {
                $data = Storage::delete('page_setting.json');
            }
            $settings = $request->all();
            foreach ($settings as $key => $setting) {
                $themeoption = \DB::table('page_options')->where('setting', $key)->first();
                if ($themeoption) {
                    if ($key == 'bannerimage' || $key == 'bannerbackground' || $key == 'whyusimage') {
                        if ($setting != null) {
                            $filename = $setting->getClientOriginalName();
                            $setting = $setting->storeAs('uploads', $filename, 'public');
                            \DB::table('page_options')->where('setting', $key)->update(['value' => '/storage/' . $setting]);
                        }
                    } else {
                        \DB::table('page_options')->where('setting', $key)->update(['value' => $setting]);
                    }
                } else {
                    if ($key == 'bannerimage' || $key == 'bannerbackground' || $key == 'whyusimage') {
                        if ($setting != null) {
                            $filename = $setting->getClientOriginalName();
                            $setting = $setting->storeAs('uploads', $filename, 'public');
                            \DB::table('page_options')->insert(['setting' => $key, 'value' => '/storage/' . $setting]);
                        }
                    } else {
                        \DB::table('page_options')->insert(['setting' => $key, 'value' => $setting]);
                    }
                }
            }
            $themeoptions = \DB::table('page_options')->get();
            $themedata = [];
            foreach ($themeoptions as $themeoption) {
                $themedata[$themeoption->setting] = $themeoption->value;
            }

            Storage::put('page_setting.json', json_encode($themedata));
            Storage::get('page_setting.json');

            return response()->json([
                'success' => true,
                'message' => "Page option updated"
            ]);
            // }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }

    public function getPageOption(Request $request)
    {
        $exists = Storage::disk('local')->has('page_setting.json');
        if ($exists) {
            $data = Storage::get('page_setting.json');
        } else {
            $themeoptions = \DB::table('page_options')->get();
            $themedata = [];
            foreach ($themeoptions as $themeoption) {
                $themedata[$themeoption->setting] = $themeoption->value;
            }
            Storage::put('page_setting.json', json_encode($themedata));
        }
        $data = Storage::get('page_setting.json');
        return response()->json([
            'success' => true,
            'options' => json_decode($data)
        ]);
    }

    public function addbooking(Request $request)
    {
        try {

            $booking = new \App\Models\Booking();
            $totalamount = 0;
            $firstpayment = 0;
            foreach ($request->slot as $key => $value) {
                $totalamount += $value['price'];
                $firstpayment += $value['advanceprice'];
            }

            $invoicedata = [];
            $invoicedata['user_id'] = $request->user['id'];
            // $invoicedata['slot_id'] = $value['id'];
            $invoicedata['status'] = 0;
            $invoicedata['amount'] = $totalamount;
            $invoicedata['firstpayment'] = $firstpayment;
            $invoicedata['description'] = '';
            $invoicedata['created_by'] = 0;
            $invoicedata['gateway'] = $request->gateway;

            // $invoice = new \App\Models\Invoice();
            // $invoice->create($invoicedata);
            $invoice = \App\Models\Invoice::create($invoicedata);
            // get last insert id
            $lastinvoice = $invoice;
            if ($request->credit > ($totalamount - $firstpayment) && isset($request->applycredit)) {
                $amount =   $totalamount - $firstpayment;
                $credittransaction = new  \App\Models\Credittransaction;
                $credittransaction->user_id = $request['user']['id'];
                $credittransaction->amount = $amount;
                $credittransaction->description = "Slot booking by user";
                $credittransaction->created_by = $request['user']['id'];
                $credittransaction->credit_type = 2;
                $credittransaction->save();

                $lastinvoice->status = 1;
                $lastinvoice->save();
                $data['user_id'] = $request->user['id'];
                $data['category_id'] = $request->category['id'];
                $data['slot_id'] = $value['id'];
                $data['date'] = $request->date;
                $data['team_id'] = $request->team_id;
                $data['invoice_id'] = $lastinvoice->id;
                $data['status'] = 'Approved';
                $data['payment_status'] = 'Paid';
                $booking->create($data);
                $data['invoice_id'] = $lastinvoice->id;
                $invoiceitemsdata = [];
                $invoiceitemsdata['invoice_id'] = $lastinvoice->id;
                $invoiceitemsdata['quantity'] = 1;
                $invoiceitemsdata['slot_id'] = $value['id'];
                $invoiceitemsdata['price'] = $value['price'];
                $invoiceitemsdata['total'] = $value['price'];
                $invoiceitemsdata['description'] = 'Booking for ' . $value['title'] . ' on ' . $request->date . ' at ' . $value['category']['name'];
                $invoiceitemsdata['status'] = 1;
                $invoiceitems = new \App\Models\InvoiceItem();
                $invoiceitems->create($invoiceitemsdata);
            } else {
                $lastinv = \App\Models\Invoice::where('id', $lastinvoice->id)->first();
                foreach ($request->slot as $key => $value) {
                    // check if booking exists and booking is more than bookings_allowed

                    $check = \App\Models\Booking::where('slot_id', $value['id'])->where('date', $request->date)->count();

                    if ($check >= $value['bookings_allowed']) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Booking not allowed for this slot'
                        ]);
                    }
                    $invoice_id = $lastinvoice->id;
                    $invoiceitemsdata = [];
                    $invoiceitemsdata['invoice_id'] = $invoice_id;
                    $invoiceitemsdata['quantity'] = 1;
                    $invoiceitemsdata['slot_id'] = $value['id'];
                    $invoiceitemsdata['price'] = $value['price'];
                    $invoiceitemsdata['total'] = $value['price'];
                    $invoiceitemsdata['description'] = 'Booking for ' . $value['title'] . ' on ' . $request->date . ' at ' . $value['category']['name'];
                    $invoiceitemsdata['status'] = 1;
                    $invoiceitems = new \App\Models\InvoiceItem();
                    $invoiceitems->create($invoiceitemsdata);
                    $data = [];
                    $data['user_id'] = $request->user['id'];
                    $data['category_id'] = $request->category['id'];
                    $data['slot_id'] = $value['id'];
                    $data['date'] = $request->date;
                    $data['team_id'] = $request->team_id;
                    $data['invoice_id'] = $invoice_id;
                    $booking->create($data);
                }
                $data['invoice_status'] = $lastinv->status;
            }

            // print_r($data); die;
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getbookings(Request $request)
    {
        try {
            $options = $request->options;

            $limit = $options['itemsPerPage'] ?? 10;
            $page = $request->page ?? 1;
            $search = $request->search ?? '';

            $resp = \App\Models\Booking::with('user', 'slot', 'team', 'invoice');
            $resp->where(function ($query) use ($search) {
                if ($search) {
                    $query->where(function ($innerQuery) use ($search) {
                        $innerQuery->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('payment_status', 'like', '%' . $search . '%')
                            ->orWhere('date', 'like', '%' . $search . '%');
                    })->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('slot', function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%')
                            ->orWhere('price', 'like', '%' . $search . '%')
                            ->orWhere('advanceprice', 'like', '%' . $search . '%')
                            ->orWhere('start_time', 'like', '%' . $search . '%')
                            ->orWhere('end_time', 'like', '%' . $search . '%');
                    });
                }
            });

            if ($options['sortBy']) {

                $resp->orderBy($options['sortBy'][0], $options['sortDesc'] ? 'desc' : 'asc');
            }
            $data = $resp->paginate($limit);

            return response()->json([
                'success' => true,
                'bookings' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deletebooking(Request $request)
    {
        try {
            $booking = \App\Models\Booking::find($request->id);
            $booking->delete();
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function editbooking(Request $request)
    {
        try {
            $booking = \App\Models\Booking::with('user')->find($request->id);
            return response()->json([
                'success' => true,
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function updatebooking(Request $request)
    {
        try {
            $value = \App\Models\Slot::find($request->slot_id);
            $check = \App\Models\Booking::where('slot_id', $request->slot_id)->where('date', $request->date)->count();
            if ($check >= $value['bookings_allowed']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not allowed for this slot'
                ]);
            }
            $booking = \App\Models\Booking::find($request->id);
            $booking->date = $request->date;
            $booking->slot_id = $request->slot_id;
            $booking->category_id = $request->category_id;
            $booking->status = $request->status;
            $booking->payment_status = $request->payment_status;

            $booking->save();
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function cancelrequest(Request $request)
    {
        try {
            $booking = \App\Models\Booking::find($request->id);
            if ($booking->status == 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking already cancelled'
                ]);
            }
            if ($booking->user_id != Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to cancel this booking'
                ]);
            }
            $canceldata = new CancellationRequest();
            $canceldata->user_id = Auth::user()->id;
            $canceldata->booking_id = $request->id;
            $canceldata->reason = $request->reason;
            $canceldata->status = 'pending';
            $canceldata->save();
            $booking->status = 'Cancellation Requested';
            $booking->save();
            return response()->json([
                'success' => true,
                'message' => 'Cancellation request sent'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function cancelbooking(Request $request)
    {
        try {
            $canceldata = CancellationRequest::where('id', $request->id)->first();
            $booking = \App\Models\Booking::with('slot', 'invoice', 'cancellation_request')->find($canceldata->booking_id);
            $currentDate = Carbon::now();
            $dateToCheck = Carbon::createFromFormat('Y-m-d', $booking->date);

            if ($dateToCheck->greaterThan($currentDate) && $booking->invoice->status == 1) {
                $credit_trans = new \App\Models\Credittransaction;
                $credit_trans->user_id = $booking->user_id;
                $credit_trans->amount = $booking->slot->price;
                $credit_trans->description = $booking->cancellation_request->reason;
                $credit_trans->credit_type = 1;
                $credit_trans->created_by = $booking->user_id;
                $credit_trans->save();

                $booking->status = "Cancelled";
                $booking->save();

                $credit =  \App\Models\Credittransaction::where(['credit_type' => 1, 'user_id' => $booking->user_id])->sum('amount');
                $debit =  \App\Models\Credittransaction::where(['credit_type' => 2, 'user_id' => $booking->user_id])->sum('amount');
                $data = \App\Models\User::find($booking->user_id);
                $data->credits = $credit - $debit;
                $data->update();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Cancelled date expired or invoice not paid'
                ]);
            }

            if ($booking->status == 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking already cancelled'
                ]);
            }
            $canceldata->status = 'approved';
            $canceldata->save();
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function cancellationrequests()
    {
        try {
            $data = CancellationRequest::with('booking', 'user')->where('status', 'pending')->get();
            return response()->json([
                'success' => true,
                'cancellations' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function rejectcancellation(Request $request)
    {
        try {
            $canceldata = CancellationRequest::where('id', $request->id)->first();
            $canceldata->status = 'rejected';
            $canceldata->save();
            $booking = \App\Models\Booking::find($canceldata->booking_id);
            $booking->status = 'Pending';
            $booking->save();
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function approvebooking(Request $request)
    {
        try {
            $booking = \App\Models\Booking::find($request->id);
            $booking->status = 'Approved';
            $booking->save();
            return response()->json([
                'success' => true,
                'message' => 'Booking approved'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function completebooking(Request $request)
    {
        try {
            $booking = \App\Models\Booking::find($request->id);
            $booking->status = 'Completed';
            $booking->save();
            return response()->json([
                'success' => true,
                'message' => 'Booking completed'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function daySales(Request $request)
    {
        try {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $allDates = [];

            // Initialize $allDates with all dates between $startDate and $endDate
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $allDates[$date->format('Y-m-d')] = [
                    'total_amount' => 0,
                    'booking_count' => 0,
                ];
            }


            // $revanue = DB::table('invoices')
            //     ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total_amount'))
            //     ->whereBetween('created_at', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            //     ->where('status', 1)
            //     ->groupBy(DB::raw('DATE(created_at)'))
            //     ->orderBy('date')
            //     ->get();
            $revanue = DB::table('payments')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total_amount'))
                ->whereBetween('created_at', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                // ->where('status', 1)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            $dailyTotals = DB::table('bookings')
                ->leftJoin('invoices', 'bookings.invoice_id', '=', 'invoices.id')
                ->selectRaw('DATE(bookings.created_at) as date, COUNT(bookings.id) as booking_count')
                ->whereBetween('bookings.created_at', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->where('bookings.status', 'Completed')
                // ->orWhere('bookings.status', 'Approved')
                ->where('invoices.status', 1)
                ->groupBy(DB::raw('DATE(bookings.created_at)'))
                ->orderBy('date')
                ->get();

            foreach ($revanue as $revTotal) {
                $allDates[$revTotal->date]['total_amount'] = $revTotal->total_amount;
            }

            foreach ($dailyTotals as $dailyTotal) {
                $allDates[$dailyTotal->date]['booking_count'] = $dailyTotal->booking_count;
            }

            $dailyTotals = collect($allDates)->map(function ($data, $date) {
                return (object) [
                    'date' => $date,
                    'booking_count' => (isset($data['booking_count']) ? $data['booking_count'] : 0),
                    'total_amount' => (isset($data['total_amount']) ? $data['total_amount'] : 0),
                ];
            })->values();

            $revanue = collect($allDates)->map(function ($data, $date) {
                return (object) [
                    'date' => $date,
                    'total_amount' => (isset($data['total_amount']) ? $data['total_amount'] : 0),
                ];
            })->values();

            $data['dailybooking'] = $dailyTotals;
            $data['revanue'] = $revanue;

            return response()->json([
                'success' => true,
                'message' => 'day sale',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile()], 500);
        }
    }

    public function contactus(Request $request)
    {
        try {

            // echo "<pre>";
            // print_r($request->all()); die;
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string|min:10|max:10',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }
            $contact = new ContactUs;
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->description = $request->message;
            $contact->save();
            return response()->json([
                'success' => true,
                'message' => 'Thanks for conncecting us',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
