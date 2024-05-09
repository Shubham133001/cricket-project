<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    //
    public function addteam(Request $request)
    {
        try {
            $team = new \App\Models\Team();
            $team->name = $request->name;
            $team->description = $request->description;
            $team->experience = $request->experience;
            $team->designation = $request->designation;
            $team->user_id = Auth::user()->id;
            if ($request->hasFile('image')) {
                $path = $this->uploadTeamImage($request->file('image'));
                $team->image = $path;
            }
            $team->save();
            return response()->json([
                'success' => true,
                'data' => $team
            ]);
        } catch (\Exception $e) {
            // Handle exception
            return response()->json([
                'success' => false,
                'message' => 'Failed to add team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getteam(Request $request)
    {
        try {
            $team = \App\Models\Team::get();
    
            return response()->json([
                'success' => true,
                'data' => $team
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teams: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateteam(Request $request)
    {
        try {
            $team = \App\Models\Team::find($request->id);
            if (!$team) {
                return response()->json([
                    'success' => false,
                    'message' => 'Team not found'
                ], 404);
            }
    
            $team->name = $request->name;
            $team->description = $request->description;
            $team->experience = $request->experience;
            $team->designation = $request->designation;
    
            if ($request->hasFile('image')) {
                $path = $this->uploadTeamImage($request->file('image'));
                $team->image = $path;
            }
    
            $team->save();
    
            return response()->json([
                'success' => true,
                'data' => $team
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteteam(Request $request)
    {
        try {
            $team = \App\Models\Team::find($request->id);
            if (!$team) {
                return response()->json([
                    'success' => false,
                    'message' => 'Team not found'
                ], 404);
            }
    
            $team->delete();
    
            return response()->json([
                'success' => true,
                'data' => $team
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getuserteam()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $team = \App\Models\Team::where('user_id', $user->id)->first();
                if ($team) {
                    return response()->json([
                        'success' => true,
                        'team' => $team
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Team not found for the authenticated user'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadTeamImage($imagefile)
    {
        $file = $imagefile;
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $path = $file->storeAs('uploads/team/image/', $filename, 'public');
        return $path;
    }
}
