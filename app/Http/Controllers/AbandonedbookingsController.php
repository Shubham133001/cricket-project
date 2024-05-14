<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Abandonedbooking;

class AbandonedbookingsController extends Controller
{
    public function addabandonedbookings(Request $request)
    {
        try {
            $abandonedbooking = new Abandonedbooking();
            $abandonedbooking->slot_id = $request->slot_id;
            $abandonedbooking->user_id = $request->user_id;
            $abandonedbooking->booking_date = $request->booking_date;
            $abandonedbooking->save();

            return response()->json(['success' => true, 'message' => 'Abandoned booking added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function removeabandonedbookings(Request $request)
    {
        try {
            $abandonedbooking = Abandonedbooking::find($request->id);
            $abandonedbooking->delete();
            return response()->json(['success' => true, 'message' => 'Abandoned booking removed successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getabandonedbookings(Request $request)
    {
        try {
            $search = $request->search;
            $options = $request->options;
            $abandonedbookings = Abandonedbooking::with(['slot', 'user']);
            if ($search) {
                $abandonedbookings->where(function ($query) use ($search) {
                    $query->whereHas('slot', function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%');
                    })->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })->orWhere('booking_date', 'like', '%' . $search . '%');
                });
            }
            if ($options['sortBy']) {
                $abandonedbookings->orderBy($options['sortBy'][0], $options['sortDesc'] ? 'desc' : 'asc');
            }
            $data = $abandonedbookings->paginate($options['itemsPerPage']);
            return response()->json(['success' => true, 'abandonedbookings' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
