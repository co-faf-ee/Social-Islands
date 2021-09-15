<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $fillable = [
      'username','password','email','IP'
    ];

    protected $dateFormat = 'U';

    public $timestamps = false;

    protected $hidden = [
        'password', 'remember_token',
    ];

    /*

    protected $guarded = [];

    */
}
