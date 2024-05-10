<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    //
    public function adduser(Request $request)
    {
        try {
            if (!auth()->check()) {
                // validate user

                $userauth = new UserAuthController();
                $user = $userauth->signin($request);
                $resp = $user->getData();
                if ($resp->success == false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not logged in'
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'User loggedin successfully'
                    ]);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'User not logged in'
                ]);
            }
            // validate user
            // $request->validate([
            //     'name' => 'required',
            //     'email' => 'required|email|unique:users',
            //     'password' => 'required',
            //     'phone' => 'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
            // ]);
            // validate custom message
            $messages = [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Email is not valid',
                'email.unique' => 'Email is already taken',
                'password.required' => 'Password is required',
                'phone.required' => 'Phone is required',
                'phone.unique' => 'Phone is already taken',
                'phone.regex' => 'Phone is not valid',
                'phone.min' => 'Phone is not valid'
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'phone' => 'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
            ], $messages);

            if ($validator->errors()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $data = new \App\Models\User();

            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->phone = $request->phone;
            $data->save();

            // add team
            $team = new \App\Models\Team();
            $team->user_id = $data->id;
            $team->name = $request->team->name;
            $team->designation = $request->team->designation;
            $team->save();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getusers(Request $request)
    {
        $search = $request->search;
        $limit = $request->itemsPerPage;
        $page = $request->page;
        
        $resp = \App\Models\User::with('team')
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query
                    ->whereHas('team', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                }
            })
            ->orderByDesc('created_at')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'users' => $resp
        ]);
    }

    public function edituser(Request $request)
    {
        $data = \App\Models\User::with('team')->find($request->id);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateuser(Request $request)
    {

        try {
            $data = \App\Models\User::find($request->id);
            $password = $request->password;
            if ($password && $password != '' && $password != null) {
                $data->password = bcrypt($password);
            }
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
        
            $data->update();
        
            $team = \App\Models\Team::where('user_id', $request->id)->first();
            $team->name = $request->team_name;
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
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteuser(Request $request)
    {

        try {
            $data = \App\Models\User::find($request->id);
            $team = \App\Models\Team::where('user_id', $request->id)->first();
            $bookings = \App\Models\Booking::where('user_id', $request->id)->get();
            if ($bookings) {
                foreach ($bookings as $booking) {
                    $booking->delete();
                }
            }
            if ($team) {
                $team->delete();
            }
            $data->delete();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() . 'on line ' . $e->getLine() . ' in file ' . $e->getFile()
            ]);
        }
    }
    public function getbookings()
    {
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'message' => 'User not logged in'
            ], 401);
        }
        $data = \App\Models\Booking::with('user', 'slot', 'team');
        $data->where('user_id', auth()->user()->id);
        $data->orderBy('id', 'desc');
        $data = $data->get();
        return response()->json([
            'success' => true,
            'bookings' => $data
        ]);
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
