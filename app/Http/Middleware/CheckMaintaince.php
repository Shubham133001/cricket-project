<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CheckMaintaince
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
        $checkadminurl = explode("/admin/", $request->header('referer'));
        if (is_array($checkadminurl) && count($checkadminurl) > 1) {
            return $next($request);
        }
        $storesetting = getStoreDetails();
        if (isset($storesetting['maintainmode']) && $storesetting['maintainmode'] == 1) {
            $rdirecturl = isset($storesetting['maintainmoderedirect']) ? $storesetting['maintainmoderedirect'] : '';
            $message = isset($storesetting['maintainmodetext']) ? $storesetting['maintainmodetext'] : 'Maintenance mode is on';
            if ($rdirecturl != '') { // redirect to custom url if set in admin panel
                return response()->json(['redirecturl' => $rdirecturl], 503);
            } else {
                return response()->json(['message' => $message], 503);
            }
            return response()->json(['message' => $message], 503);
        }
        if (Auth::check() == false) {
            if (in_array($request->route()->getActionMethod(), ['viewinvoice', 'viewpdf', 'download', 'getarticle', 'getAnnouncement', 'getproductbyid', 'getproductbyslug', 'publicdownloadfile', 'getCustomFields', 'ticketdetails', 'ticketanswer', 'ticketclose'])) {
                return $next($request);
            }
            $checkviewurl = explode("/invoice/view/", $request->header('referer'));
            if (is_array($checkviewurl) && count($checkviewurl) > 1) {
                return $next($request);
            }
            //check ticket url 
            $checkticketurl = explode("/newticket", $request->header('referer'));
            if (is_array($checkticketurl) && count($checkticketurl) > 1) {
                return $next($request);
            }
            $asktologin = getStoreDetails('asktologin');
            if ($asktologin && $asktologin->value == 1) {
                $checkloginurl = explode("/auth/", $request->header('referer'));
                if (count($checkloginurl) == 1 && in_array($request->path(), ['api/store', 'api/getcaptcha', 'api/invoicetaxrate', 'api/get-profile-fields', 'api/paymentmethods', 'api/countries', 'api/states', 'api/cities', 'api/currencies', 'api/getDefaultCurrency', 'api/languages', 'api/usergroups', 'api/departments', 'api/products', 'api/products/group', 'api/getTax', 'api/getTaxRates', 'api/invoicetaxrate', 'api/getcartTaxRates', 'api/coupon/verify', 'api/checkout', 'api/carttotal', 'api/sendcontactusmail', 'api/generateticket'])) {
                    return response()->json(['message' => __('auth.pleaseLogin')], 401);
                }
            }
        }
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if ($token) {
            $checkuser = $token->tokenable;
            if ($checkuser) {
                $auth = Auth::guard('web')->loginUsingId($checkuser->id);
                return $next($request);
            }
            return $next($request);
        }
        return $next($request);
    }
}
