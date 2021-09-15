<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $fillable = [
      'name', 'desc', 'price','img_url','created','last_updated','rare','rare_quantity','offsale','gold_only','layer'
    ];

    protected $dateFormat = 'U';

    public $timestamps = false;

    protected $table = 'store';
}
