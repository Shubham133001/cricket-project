<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB as Capsule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getGuardNames()
    {
        return $this->guard;
    }
    public function team()
    {
        return $this->hasOne(Team::class, 'user_id', 'id');
    }

    // public function credit()
    // {
    //     return $this->hasMany(Credittransaction::class, 'user_id', 'id')->where(function ($query) {
    //         $query->where('cradit_type', 1)->sum('amount'); 
    //     });
    // }
    

    // public function debit()
    // {
    //     return $this->hasMany(Credittransaction::class, 'user_id', 'id')->where(function ($query) {
    //         $query->where('cradit_type', 2)->sum('amount'); 
    //     });
    // }

    public function getTokenVelidate($email, $token)
    {
        $tokendata = Capsule::table('password_resets')->where('email', $email)->first();
        if ($tokendata && Hash::check($token, $tokendata->token)) {
            $isAuthenticated = Carbon::parse($tokendata->created_at)->addMinutes(config('auth.passwords.users.expire'))->isPast();
            if ($isAuthenticated) {
                echo 'fails';
                exit;
            } else {
                echo 'success';
                exit;
            }
        } else {
            echo 'fails';
            exit;
        }
    }

}
