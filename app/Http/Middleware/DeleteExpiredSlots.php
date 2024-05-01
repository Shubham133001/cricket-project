<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Blockappslots;
use Illuminate\Support\Facades\Schema;

class DeleteExpiredSlots
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

        $currentDate = Carbon::now()->toDateString();
        if(Schema::hasTable('blockappslots')){

            $count =  Blockappslots::count();
            if($count){
                Blockappslots::whereRaw("CURRENT_DATE = bookingdate  AND CURRENT_TIME >= SUBSTRING_INDEX(timeslots, ' ', 1) OR bookingdate  < CURRENT_DATE")->delete();
            }
           
        }
       
        return $next($request);
    }
}
