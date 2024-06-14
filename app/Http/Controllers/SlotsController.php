<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Nette\Utils\Arrays;
use Carbon\Carbon;

class SlotsController extends Controller
{
    public function add(Request $request)
    {
        try {
            $postdata = $request->all();
            $slots = $postdata['slots'];
            if (isset($request->ismultiple) && $request->ismultiple == 'true') {

                foreach ($request->date as $date) {
                    foreach ($slots as $slot) {
                        // check if start time is less than end time
                        $insertdata = [];
                        $insertdata['title'] = $slot['title'];
                        $insertdata['price'] = $slot['price'];
                        $insertdata['advanceprice'] = $slot['advanceprice'];
                        $insertdata['start_time'] = date('H:i:s', strtotime($slot['time_start']));
                        $insertdata['end_time'] = date('H:i:s', strtotime($slot['time_end']));
                        $insertdata['bookings_allowed'] = $slot['bookings_allowed'];
                        if (isset($slot['days'])) {
                            $insertdata['days'] = (gettype($slot['days']) == 'array') ? implode(',', $slot['days']) : $slot['days'];
                        } else {
                            $insertdata['days'] = implode(',', $request->days);
                        }

                        $insertdata['start_date'] = $date;
                        $insertdata['end_date'] = $date;
                        $insertdata['category_id'] = $postdata['id'];
                        $insertdata['status'] = 'Active';
                        $data = \App\Models\Slot::create($insertdata);
                    }
                }
            } else {
                if ($request->slotType == 'Date Range') {
                    // get dates between start and end date
                    $startDate = Carbon::parse($request->date[0]);
                    $endDate = Carbon::parse($request->date[1]);
                    $days = $request->days;
                    foreach ($days as $key => $day) {
                        if ($day == 0) {
                            $days[$key] = 7;
                        }
                    }
                    // Array to hold the filtered dates
                    $filteredDates = [];

                    // Function to filter dates for days
                    $filterFunction = function ($date) use ($days) {
                        return in_array($date->format('N'), $days);
                    };


                    // Loop through each day between $startDate and $endDate
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        // Check if the date matches the filter function
                        if ($filterFunction($date)) {

                            foreach ($slots as $slot) {
                                // check if start time is less than end time
                                // $date = $date->toDateString();
                                $day = $date->format('N');
                                $insertdata = [];
                                $insertdata['title'] = $slot['title'];
                                $insertdata['price'] = $slot['price'];
                                $insertdata['advanceprice'] = $slot['advanceprice'];
                                $insertdata['start_time'] = date('H:i:s', strtotime($slot['time_start']));
                                $insertdata['end_time'] = date('H:i:s', strtotime($slot['time_end']));
                                $insertdata['bookings_allowed'] = $slot['bookings_allowed'];
                                $insertdata['days'] = $day;
                                if (gettype($request->date) == 'array') {
                                    $insertdata['start_date'] = $date->toDateString();
                                    $insertdata['end_date'] = $date->toDateString();
                                } else {
                                    $insertdata['start_date'] = $request->date;
                                    $insertdata['end_date'] = $request->date;
                                }
                                if (strtotime($insertdata['start_date']) >= strtotime($insertdata['end_date'])) {
                                    // swap start and end time
                                    $temp = $insertdata['start_date'];
                                    $insertdata['start_date'] = $insertdata['end_date'];
                                    $insertdata['end_date'] = $temp;
                                }

                                $insertdata['category_id'] = $postdata['id'];
                                $insertdata['status'] = 'Active';
                                $data = \App\Models\Slot::create($insertdata);
                            }
                        }
                    }
                } else {
                    foreach ($slots as $slot) {
                        // check if start time is less than end time

                        $insertdata = [];
                        $insertdata['title'] = $slot['title'];
                        $insertdata['price'] = $slot['price'];
                        $insertdata['advanceprice'] = $slot['advanceprice'];
                        $insertdata['start_time'] = date('H:i:s', strtotime($slot['time_start']));
                        $insertdata['end_time'] = date('H:i:s', strtotime($slot['time_end']));
                        $insertdata['bookings_allowed'] = $slot['bookings_allowed'];
                        if (isset($slot['days'])) {
                            $insertdata['days'] = (gettype($slot['days']) == 'array') ? implode(',', $slot['days']) : $slot['days'];
                        } else {
                            $insertdata['days'] = implode(',', $request->days);
                        }
                        if (gettype($request->date) == 'array') {
                            $insertdata['start_date'] = $request->date[0];
                            $insertdata['end_date'] = $request->date[1];
                        } else {
                            $insertdata['start_date'] = $request->date;
                            $insertdata['end_date'] = $request->date;
                        }
                        if (strtotime($insertdata['start_date']) >= strtotime($insertdata['end_date'])) {
                            // swap start and end time
                            $temp = $insertdata['start_date'];
                            $insertdata['start_date'] = $insertdata['end_date'];
                            $insertdata['end_date'] = $temp;
                        }

                        $insertdata['category_id'] = $postdata['id'];
                        $insertdata['status'] = 'Active';
                        $data = \App\Models\Slot::create($insertdata);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile()
            ]);
        }
    }
    public function getallslots(Request $request)
    {
        try {
            $options = $request->options;
            $limit = $options['itemsPerPage'] ?? 10;
            $page = $request->page ?? 1;
            $search = $request->search ?? '';
            $resp = \App\Models\Slot::with('category')
                ->where(function ($query) use ($search) {
                    if ($search) {
                        $query->orWhere('title', 'like', '%' . $search . '%')
                            ->orWhere('start_time', 'like', '%' . $search . '%')
                            ->orWhere('end_time', 'like', '%' . $search . '%')
                            ->orWhere('start_date', 'like', '%' . $search . '%')
                            ->orWhere('end_date', 'like', '%' . $search . '%')
                            ->orWhere('price', 'like', '%' . $search . '%')
                            ->orWhere('advanceprice', 'like', '%' . $search . '%')
                            ->orWhereHas('category', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    }
                })
                ->orderByDesc('created_at')
                ->paginate($limit);

            $resp->map(function ($item) {
                $item->slot_time = $item->start_time . " - " . $item->end_time;
                $item->slot_date = $item->start_date . " - " . $item->end_date;
                $item->days = explode(',', $item->days);
                return $item;
            });

            return response()->json([
                'success' => true,
                'slots' => $resp
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    public function getslots(Request $request)
    {
        // get day from date
        try {
            $date = strtotime($request->date);
            $date = date('Y-m-d', $date);

            $data = \App\Models\Slot::where('category_id', $request->id)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->with(['bookings' => function ($query) use ($date) {
                    $query->where('date', $date)->where('status', '!=', 'Cancelled')->with(['user', 'team']);
                }])
                ->with('category')
                ->get();

            $data->map(function ($item) {
                $item->days = explode(',', $item->days);
                return $item;
            });

            return response()->json([
                'success' => true,
                'slots' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    public function getslots1(Request $request)
    {
        // get day from date
        try {
            $date = strtotime($request->date);
            $date = date('Y-m-d', $date);

            $data = \App\Models\Slot::where('category_id', $request->id)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->with(['bookings' => function ($query) use ($date) {
                    $query->where('date', $date)->where('status', '!=', 'Cancelled')->with(['user', 'team']);
                }])
                ->with('category')
                ->get();
            $newdata = [];
            $requestedday = $request->day;
            if ($requestedday == 0) {
                $requestedday = 7;
            }
            foreach ($data as $key => $value) {
                $value->days = explode(',', $value->days);

                if (in_array($requestedday, $value->days)) {
                    array_push($newdata, $value);
                }
            }
            return response()->json([
                'success' => true,
                'slots' => $newdata
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function slotsWithCatID(Request $request)
    {
        try {
            $date = strtotime($request->date);
            $date = date('Y-m-d', $date);
            $data = \App\Models\Slot::where('category_id', $request->id)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->with(['bookings' => function ($query) use ($date) {
                    $query->where('date', $date)->where('status', '!=', 'Cancelled')->with(['user', 'team']);
                }])
                ->with('category')
                ->get();
            $data->map(function ($item) {
                $item->slot_time = $item->start_time . " - " . $item->end_time;
                $item->slot_date = $item->start_date . " - " . $item->end_date;
                $item->days = explode(',', $item->days);
                return $item;
            });

            return response()->json([
                'success' => true,
                'slots' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    public function getallslotsforcategory(Request $request)
    {
        try {
            // get day from date
            //  $date = strtotime($request->date);
            //  $date = date('Y-m-d', $date);  
            // $data = \App\Models\Category::where('id',$request->id)->get();
            // $list = [];
            // foreach ($data as $key => $value) {
            //     $value->slot_count = \App\Models\Slot::where('category_id', $value->id)->count();
            //     $value->dates = \App\Models\Slot::where('category_id', $value->id)->select('start_date')->groupBy('start_date')->get();
            //     foreach ($value->dates as $key => $date) {
            //         $date->slots = \App\Models\Slot::where('category_id', $value->id)->where('start_date', $date->start_date)->get();
            //         $date->slots->map(function ($item) {
            //             $item->slot_time = $item->start_time . " - " . $item->end_time;
            //             $item->slot_date = $item->start_date . " - " . $item->end_date;
            //             $item->days = explode(',', $item->days);
            //             return $item;
            //         });
            //     }
            //     $list[] = $value;
            // }

            $date = strtotime($request->date);
            $date = date('Y-m-d', $date);
            $data = \App\Models\Slot::where(
                'category_id',
                $request->id
            )->with('category')->get();

            $data->map(function ($item) {
                // $item->time = explode('-', $item->time);
                $item->days = explode(',', $item->days);
                // $item->slotdate = explode(',', $item->slotdate);
                return $item;
            });

            return response()->json([
                'success' => true,
                'slots' => $data
            ]);
        } catch (\Exception $e) {
            // Handle the exception
            return response()->json([
                'success' => false,
                'error' => $e->getMessage() // You can customize the error message as needed
            ]);
        }
    }


    public function slotsforcategory(Request $request)
    {
        try {
            //  get day from date
            $date = strtotime($request->date);
            $date = date('Y-m-d', $date);
            $data = \App\Models\Category::where('id', $request->id)->get();
            $list = [];
            foreach ($data as $key => $value) {
                $value->slot_count = \App\Models\Slot::where('category_id', $value->id)->count();
                $value->dates = \App\Models\Slot::where('category_id', $value->id)->select('start_date')->groupBy('start_date')->get();
                foreach ($value->dates as $key => $date) {
                    $date->slots = \App\Models\Slot::where('category_id', $value->id)->where('start_date', $date->start_date)->get();
                    $date->slots->map(function ($item) {
                        $item->slot_time = $item->start_time . " - " . $item->end_time;
                        $item->slot_date = $item->start_date . " - " . $item->end_date;
                        $item->days = explode(',', $item->days);
                        return $item;
                    });
                }
                $list[] = $value;
            }

            // $date = strtotime($request->date);
            // $date = date('Y-m-d', $date);
            // $data = \App\Models\Slot::where(
            //     'category_id',
            //     $request->id
            // )->with('category')->get();

            // $data->map(function ($item) {
            //     // $item->time = explode('-', $item->time);
            //     $item->days = explode(',', $item->days);
            //     // $item->slotdate = explode(',', $item->slotdate);
            //     return $item;
            // });

            return response()->json([
                'success' => true,
                'slots' =>  $list
            ]);
        } catch (\Exception $e) {
            // Handle the exception
            return response()->json([
                'success' => false,
                'error' => $e->getMessage() // You can customize the error message as needed
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            \App\Models\Booking::where('slot_id', $request->id)->delete();
            $data = \App\Models\Slot::find($request->id);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Slot deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
