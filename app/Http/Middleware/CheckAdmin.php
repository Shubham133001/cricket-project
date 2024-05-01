<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Route;

class CheckAdmin
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
        
        if (Route::currentRouteName() == 'admin.invoice.duedate') {
            return $next($request);
        }
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if ($token && $token->tokenable_type == 'App\Models\User') {
            return response()->json([
                __('auth.notAuthorized')
            ], 401);
        }
        if ($token && Auth::user()->getGuardNames() == 'user') {
           
            Auth::guard('admin')->loginUsingId($token->tokenable_id);
            return $next($request);

        } else if ($token && $token->tokenable_type == 'App\Models\Admin') {

            Auth::guard('admin')->loginUsingId($token->tokenable_id);
            
            if (Auth::guard('admin')->user()->admingroup->id == 1) {
                return $next($request);
            }
            
            if (Route::currentRouteName() == 'admin.me') {
                return $next($request);
            }

            if (Route::currentRouteName() == 'storedetails.store') {
                return $next($request);
            }
        
            if (Route::currentRouteName() == 'api.store') {
                return $next($request);
            }     
            
            if (Route::currentRouteName() == 'admin.checkPermission') {
                return $next($request);
            }
           
            if ($request->route()->uri() == 'api/admin/users') {
                $requestedurl = 'api/client';
            } else {
                $requestedurl = $request->route()->uri();
            }
         
            $checkpermission = \App\Models\AdminPermission::where('admin_group_id', Auth::guard('admin')->user()->admingroup->id)->where('url', $requestedurl)->first();
            
            if ($checkpermission || $checkpermission != null) {
                
                return $next($request);

            } else {
           
                return response()->json(['message' => __('auth.notAuthorizedMessage')], 403);
            }
           
            return $next($request);
       
        } else {
       
            return response()->json([
                __('auth.notAuthorized')
            ], 401);
        }
    }
}
