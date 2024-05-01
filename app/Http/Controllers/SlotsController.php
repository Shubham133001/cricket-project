<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Nette\Utils\Arrays;

class SlotsController extends Controller
{
    //
    public function add(Request $request)
    {
        try {
            $postdata = $request->all();
            $slots = $postdata['slots'];

            foreach ($slots as $slot) {
                // check if start time is less than end time

                $insertdata = [];
                $insertdata['title'] = $slot['title'];
                $insertdata['price'] = $slot['price'];
                $insertdata['advanceprice'] = $slot['advanceprice'];
                $insertdata['start_time'] = $slot['time_start'];
                $insertdata['end_time'] = $slot['time_end'];
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

                // $insertdata['start_date'] = $slot['startdate'];
                // $insertdata['end_date'] = $slot['enddate'];
                $insertdata['category_id'] = $postdata['id'];
                $insertdata['status'] = 'Active';
                $data = \App\Models\Slot::create($insertdata);
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
        $data = \App\Models\Slot::with('category')->get();
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
    }
    public function getslots(Request $request)
    {
        // get day from date
        $date = strtotime($request->date);
        $date = date('Y-m-d', $date);
        $data = \App\Models\Slot::where(
            'category_id',
            $request->id
        )->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->with('bookings', function ($query) use ($date) {
            $query->where('date', $date)->with(['user', 'team']);
        })->with('category')->get();
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
    }

    public function getallslotsforcategory(Request $request)
    {
        // get day from date
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
    }

    public function delete(Request $request)
    {
        try {
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
