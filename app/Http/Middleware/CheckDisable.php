<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CheckDisable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('IsAdminLogin') == 1) {
            return $next($request);
        }
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if ($token && $token->tokenable_type == 'App\Models\Admin') {
            return $next($request);
        }
        $user = Auth::user();
        if ($user->status == 0) {
            // Auth::logout();
            auth()->guard('api')->user()->tokens()->delete();
            return response()->json(['message' => 'auth.accountDisabled'], 401);
        }
        return $next($request);
    }
}
