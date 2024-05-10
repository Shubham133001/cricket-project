<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credittransaction;

class CreditsController extends Controller
{
    //
    public function addcredits(Request $request)
    {
        $user = \App\Models\User::find($request->user_id);
        $user->credits += $request->amount;
        $user->save();

        $creditTransaction = new Credittransaction();
        $creditTransaction->user_id = $user->id;
        $creditTransaction->amount = $request->amount;
        $creditTransaction->description = $request->description;
        $creditTransaction->created_by = $request->created_by;
        $creditTransaction->save();

        return response()->json(['message' => 'Credits added successfully']);
    }

    public function deductcredits(Request $request)
    {
        $user = \App\Models\User::find($request->user_id);
        $user->credits -= $request->amount;
        $user->save();

        $creditTransaction = new Credittransaction();
        $creditTransaction->user_id = $user->id;
        $creditTransaction->amount = $request->amount;
        $creditTransaction->description = $request->description;
        $creditTransaction->created_by = $request->created_by;
        $creditTransaction->save();

        return response()->json(['message' => 'Credits deducted successfully']);
    }
}
