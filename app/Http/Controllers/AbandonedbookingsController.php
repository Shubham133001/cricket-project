<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Abandonedbooking;

class AbandonedbookingsController extends Controller
{
    //
    public function addabandonedbookings(Request $request)
    {
        $abandonedbooking = new Abandonedbooking();
        $abandonedbooking->slot_id = $request->slot_id;
        $abandonedbooking->user_id = $request->user_id;
        $abandonedbooking->booking_date = $request->booking_date;
        $abandonedbooking->save();

        return response()->json(['success' => true, 'message' => 'Abandoned booking added successfully']);
    }

    public function removeabandonedbookings(Request $request)
    {
        $abandonedbooking = Abandonedbooking::find($request->id);
        $abandonedbooking->delete();

        return response()->json(['success' => true, 'message' => 'Abandoned booking removed successfully']);
    }

    public function getabandonedbookings(Request $request)
    {
        $search = $request->search;
        $options = $request->options;
        $abandonedbookings = Abandonedbooking::with('slot', 'user');
        if ($search != '') {
            $abandonedbookings = Abandonedbooking::with(['slot' => function ($q, $search) {
                $q->where('name', 'like', '%' . $search . '%');
            }, 'user' => function ($q, $search) {
                $q->where('name', 'like', '%' . $search . '%');
            }]);
            $abandonedbookings->orWhere('date', 'like', '%' . $search . '%');
        }
        if ($options['sortBy']) {
            $abandonedbookings->orderBy($options['sortBy'], $options['sortDesc'] ? 'desc' : 'asc');
        }
        $data = $abandonedbookings->paginate($options['itemsPerPage']);
        return response()->json(['success' => true, 'abandonedbookings' => $data]);
    }
}
