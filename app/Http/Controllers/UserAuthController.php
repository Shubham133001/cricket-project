<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

class UserAuthController extends Controller
{
    use HasApiTokens;
    use Notifiable;
    //
    public function signin(Request $request)
    {
        try {
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
        try {
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
            if (isset($request->team)) {
                $team = new \App\Models\Team();
                $team->user_id = $user->id;
                $team->name = $request->team['name'];
                $team->designation = $request->team['designation'];
                $team->save();
            }
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

    public function signout(Request $request)
    {
        try {
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
        try {
            if (!auth()->user()) {
                self::signout();
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
            $user = auth()->user();
            $credit =  \App\Models\Credittransaction::where(['credit_type' => 1, 'user_id' => auth()->user()->id])->sum('amount');
            $debit =  \App\Models\Credittransaction::where(['credit_type' => 2, 'user_id' => auth()->user()->id])->sum('amount');

            $credits = $credit - $debit;
            $data = \App\Models\User::with('team')->findOrFail(auth()->user()->id);
            $data->credits = $credits;
            $data->save();
            $user->credits = $credits;
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function forgotpassword(Request $request)
    {
        try {
            $messages = [
                'email.required' => 'Email is required',
                'email.email' => 'Email is not valid',
                'email.exists' => 'Email is not registered'
            ];
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users'
            ], $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $user = \App\Models\User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
            // $status = Password::sendResetLink(
            //     $request->only('email')
            // );
            // use notification
            $notification = $user->notify(new ResetPasswordNotification(Password::createToken($user)));
            // return response
            return response()->json([
                'success' => true,
                'message' => 'Reset password link sent to your email'
            ]);
            // return $status === Password::RESET_LINK_SENT
            //     ? response()->json(['success' => true, 'message' => __($status)], 200)
            //     : response()->json(['success' => false, 'message' => __($status)], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function resetpassword(Request $request)
    {
        try {
            $messages = [
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 6 characters'
            ];
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:6'
            ], $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }
            // get user with token
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password)
                    ])->save();
                    event(new PasswordReset($user));
                }
            );
            return $status == Password::PASSWORD_RESET
                ? response()->json(['success' => true, 'message' => __($status)], 200)
                : response()->json(['success' => false, 'message' => __($status)], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function authGoogle(Request $request)
    {
        try {
            $storedetails = getstoredetails();
            $client_id = $storedetails['clientid'];
            $client_secret = $storedetails['clientsecret'];
            if (!$request->code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ]);
            }
            $code = $request->code;
            // $client_id = "436364067035-8ik3b0ubtjd9lci69ivn319go0jhookm.apps.googleusercontent.com";
            // $client_secret = "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";

            $url = "https://oauth2.googleapis.com/token";
            $post_fields = [
                'code' => $code,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => url('/user/auth/google'),
                'grant_type' => 'authorization_code'
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            $access_token = $response->access_token;
            $url = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $access_token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            // check if user exists
            $user = \App\Models\User::where('email', $response->email)->first();
            if (!$user) {
                $user = new \App\Models\User();
                $user->name = $response->name;
                $user->email = $response->email;
                $user->email_verified_at = now();
                $user->credits = 0;
                $user->save();
            }
            $token = $user->createToken('authToken')->plainTextToken;
            if ($token) {
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'token' => $token
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
