<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public $table = 'users';
    /**
     * The attributes that are mass assignable.
     *可批量赋值属性
     * @var array
     */
//    protected $fillable = [
//        'phone'
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }



    public function getJWTCustomClaims()
    {
        return [];
    }

}
