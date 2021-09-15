<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Islands extends Model
{
  protected $dateFormat = 'U';

  public $timestamps = false;

  protected $fillable = [
    'serverName','description','password','maxplayers','locked','active','copylocked','goldonly'
  ];
}
