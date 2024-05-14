<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class UserAuthController extends Controller
{
    use HasApiTokens;
    //
    public function signin(Request $request)
    {
        try{
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }

    public function signup(Request $request)
    {
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|min:10|max:10',
            'team.name' => 'required|string',
            'team.designation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->save();

        $team = new \App\Models\Team();
        $team->user_id = $user->id;
        $team->name = $request->team['name'];
        $team->designation = $request->team['designation'];
        $team->save();

        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }

    public function signout()
    {
        try{
        if (!auth()->user()) {
            // get token from request headers
            $token = $request->bearerToken();
            // get token from database
            $token = $user->tokens()->where('id', $token)->first();
            // delete token
            $token->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        }
        auth()->user()->tokens()->delete();
        // auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }

    public function me()
    {
        try{
        if (!auth()->user()) {
            self::signout();
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
        $user = auth()->user();
        $credit =  \App\Models\Credittransaction::where(['credit_type'=> 1 , 'user_id'=>auth()->user()->id])->sum('amount');
        $debit =  \App\Models\Credittransaction::where(['credit_type'=> 2 , 'user_id'=>auth()->user()->id])->sum('amount');
        $user->credits = $credit - $debit;
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    }
}
