<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    //
    public function addteam(Request $request)
    {
        $team = new \App\Models\Team();
        $team->name = $request->name;
        $team->description = $request->description;
        $team->experience = $request->experience;
        $team->designation = $request->designation;
        $team->user_id = Auth::user()->id;
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $destination = storage_path('app/public/uploads/team');
            $file->move($destination, $filename);
            $team->image = $filename;
        }
        $team->save();
        return response()->json([
            'success' => true,
            'data' => $team
        ]);
    }

    public function getteam(Request $request)
    {
        $team = \App\Models\Team::get();
        return response()->json([
            'success' => true,
            'data' => $team
        ]);
    }

    public function updateteam(Request $request)
    {
        $team = \App\Models\Team::find($request->id);
        $team->name = $request->name;
        $team->description = $request->description;
        $team->experience = $request->experience;
        $team->designation = $request->designation;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $destination = storage_path('app/public/uploads/team');
            $file->move($destination, $filename);
            $team->image = $filename;
        }
        $team->save();
        return response()->json([
            'success' => true,
            'data' => $team
        ]);
    }

    public function deleteteam(Request $request)
    {
        $team = \App\Models\Team::find($request->id);
        $team->delete();
        return response()->json([
            'success' => true,
            'data' => $team
        ]);
    }

    public function getuserteam()
    {
        if (Auth::user()) {
            $user = Auth::user();
            $team = \App\Models\Team::where('user_id', $user->id)->first();
            return response()->json([
                'success' => true,
                'team' => $team
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
        }
    }
}
