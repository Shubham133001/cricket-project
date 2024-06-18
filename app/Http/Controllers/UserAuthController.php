<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Password;
use App\Notifications\CustomResetPassword;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Oauth2Service;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;



class UserAuthController extends Controller
{
    use HasApiTokens;
    use SendsPasswordResetEmails;
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

        if ($request['email'] !== null || !empty($request['email'])) {
           $user = User::where('email', $request['email'])->firstOrFail();
           $token = Password::getRepository()->create($user);
           $details['url'] = url('api/reset-password?token='.$token."&email=".$request['email']);
            Mail::to($request['email'])->send(new SendEmail($details['url']));
            return response()->json(['success' => true, 'message' => "Forgot email sent"]);
        } else {
            return response()->json(['success' => false, 'message' => "email not provided"]);
        }
    }

    public function resetpassword(Request $request)
    {
        if ($request['password'] !== null) {
            try {
                $user = User::where('email', $request['email'])->firstOrFail();
                $token = Password::getRepository()->exists($user, $request['token']);
                if (!$token) {
                    return response()->json(['success' => false, 'message' => "Invalid token"]);
                }
                $status = Password::reset(
                    $request->only('email', 'password', 'token'),
                    function ($user, $password) use ($request) {
                        $user->forceFill([
                            'password' => Hash::make($password)
                        ])->save();
                    }
                );
                if ($status) {
                    return response()->json(['success' => true, 'message' => "password reset sent"]);
                } else {
                    return response()->json(['success' => false, 'message' => "password reset faild"]);
                }
            } catch (\Throwable $th) {
                return response()->json(['success' => false, 'message' => $th->getMessage()]);
            }
        } else {
            return response()->json(['success' => false, 'message' => __('apiresponse.emptyPassword')]);
        }
    }

    public function resendverificationemail(Request $request)
    {
        if (isset($request['email']) && !empty($request['email'])) {
            try {
                $user = User::where('email', $request['email'])->firstOrFail();
                if ($user->email_verified_at != null) {
                    return response()->json(['success' => false, 'message' => __('apiresponse.alreadyVerified')]);
                }
                $user->sendEmailVerificationNotification();
                return response()->json(['success' => true, 'message' => __('apiresponse.emailsent')]);
            } catch (\Throwable $th) {
                return response()->json(['success' => false, 'message' => $th->getMessage()]);
            }
        }
        if ($request['id'] !== null || !empty($request['id'])) {
            $user = User::find($request['id']);

            $user->sendEmailVerificationNotification();
            return response()->json(['success' => true, 'message' => __('apiresponse.emailsent')]);
        } else {
            return response()->json(['success' => false, 'message' => __('apiresponse.emailnotprovided')]);
        }
    }

    public function verifyresetpasswordtoken(Request $request)
    {
        if ($request['token'] !== null || !empty($request['token'])) {
            $usermodel = new User();
            $user = $usermodel->getTokenVelidate($request['email'], $request['token']);
            if ($user == 'success') {
                return response()->json(['success' => true, 'message' => __('apiresponse.tokenVerified')]);
            }
        } else {
            return response()->json(['success' => false, 'message' => __('apiresponse.tokenNotVerified')]);
        }
    }


    public function loginwithgoogle(Request $request)
    {
        if (!isset($request['google_code']) && $request['google_code'] == '') {
            return response()->json(['success' => false, 'message' => "Invalid request"]);
        }
        try {

            $redirecturl = url('/auth/googleoauth');
            $storename = getStoreDetails('name');
            $gClient = new Google_Client();
            $gClient->setApplicationName('Login to ' . $storename);
            $gClient->setClientId("436364067035-8ik3b0ubtjd9lci69ivn319go0jhookm.apps.googleusercontent.com");
            $gClient->setClientSecret("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");
            $gClient->setRedirectUri($redirecturl);
            $gClient->authenticate($request['google_code']);
            $token = $gClient->getAccessToken();
            $gClient->setAccessToken($token);
            $google_oauthV2 = new Google_Oauth2Service($gClient);
            $gpUserProfile = $google_oauthV2->userinfo->get();
            $user = User::where('email', $gpUserProfile['email'])->first();
            if ($user) {
                Auth::guard('web')->loginUsingId($user->id);
                $user->save();
                return response()->json(['success' => true, 'message' => "User Login successfully", 'user' => $user, 'access_token' => $user->createToken('auth_token')->plainTextToken, 'token_type' => 'Bearer']);
            } else {
                $data = array();
                $data['name'] = $gpUserProfile['given_name'];
                $data['email'] = $gpUserProfile['email'];
               // $data['phone'] = $gpUserProfile['id'];
               // $data['password'] = Hash::make("Dummy");
                $data['oauth_provider'] = 'google';
                $data['oauth_uid'] = $gpUserProfile['id'];
                return $this->commonsignup($data);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage() . '' . $th->getLine() . '' . $th->getFile()]);
        }
    }

    public function getLoginUrl()
    {
        $authUrlg = '';
        $redirecturl = url('/auth/googleoauth');
        $storename = getStoreDetails('companyname');
        $gClient = new Google_Client();
        $gClient->setApplicationName('Login to ' . $storename);
        $gClient->setClientId("436364067035-8ik3b0ubtjd9lci69ivn319go0jhookm.apps.googleusercontent.com");
        $gClient->setClientSecret("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");
        $gClient->setRedirectUri($redirecturl);
        $gClient->addScope('email');
        $gClient->addScope('profile');
        $authUrlg = $gClient->createAuthUrl(); // to get login url
        return response()->json(['success' => true, 'googlelogin' => $authUrlg, 'appleclientid' =>
        "test", 'appleredirect' => $redirecturl, 'googleclientid' => "436364067035-8ik3b0ubtjd9lci69ivn319go0jhookm.apps.googleusercontent.com"]);
    }

    private function commonsignup($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        //$user->password = $data['password'];
        $user->oauth_provider = $data['oauth_provider'];
        $user->oauth_uid = $data['oauth_uid'];
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();
        Auth::guard('web')->loginUsingId($user->id);
        //$user->last_seen = date('Y-m-d H:i:s');
        $user->save();
        $getuser = User::where('id', $user->id)->first();
        return response()->json(['success' => true, 'message' => "User Login successfully", 'user' => $getuser, 'access_token' => $user->createToken('auth_token')->plainTextToken, 'token_type' => 'Bearer']);
    }
}
