<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    //auth login with sanctum
    public function signin(Request $request)
    {
        try {
            $user = Admin::where('email', $request['email'])->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Login Details'
                ], 401);
            }

            if ($user->status == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not active'
                ], 401);
            }

            $request->merge([
                'email' => trim($request->email),
                'password' => trim($request->password),
            ]);

            if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
                $details['email'] = $request->email;
                $details['loginip'] = $request->ip();
                $details['browser'] = $request->userAgent();
                $details['date'] = date('Y-m-d H:i:s');
                $details['template'] = 'admin.login';
                return  response()->json([
                    'success' => false,
                    'message' => 'Invalid login details'
                ], 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user(); //guard('admin')
        // check if current password match
        if (!Hash::check($request->currentpass, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password does not match!'
            ], 200);
        }
        try {
            if ($user) {
                $user->name = $request->name;
                // if password is not empty then hash and update the password
                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Profile Updated Successfully.'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 401);
        }
    }


    public function logout(Request $request)
    {
        $user = Auth::user(); //guard('admin')
        try {
            if ($user) {
                if (Auth::check()) {
                    auth()->guard('admin')->user()->tokens()->delete();
                    Auth::guard('admin')->logout();
                }
            }
            return response()->json([
                'message' => 'Logout Successfully.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 401);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = Admin::with('admingroup')->find(Auth::user()->id);
            if ($user) {
                return response()->json(['userdata' => $user, 'success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'User is Login']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
