<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Retrieve their profiles
     */
    public function profiles()
    {
        return $this->hasMany('App\Profile');
    }

    /**
     * Retrieve their linked socials
     */
    public function socials()
    {
        return $this->hasMany('App\Social');
    }
}
